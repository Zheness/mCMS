<?php
namespace Mcms\Modules\Cli\Tasks;

use Mcms\Library\Tools;
use Mcms\Models\Member;
use Mcms\Models\Option;
use Mcms\Models\SpecialPage;
use Phalcon\Cli\Task;
use Phalcon\Config\Factory;
use Phalcon\Validation;

class InstallTask extends Task
{
    public function mainAction()
    {
        $nbMembers = Member::count();
        if ($nbMembers) {
            echo "[ERROR] Installation cancelled. It seems that the website is already configured.";
            return false;
        }

        if (!file_exists(APP_PATH . "/config/install.ini")) {
            $content = <<<EOL
[admin]
firstname = 
lastname = 
email = 
password = 
username =
EOL;
            $saved = file_put_contents(APP_PATH . "/config/install.ini", $content);
            if ($saved === false) {
                echo "[ERROR] Cannot create install.ini file, please check the permissions.";
                return false;
            }
            echo "A file install.ini has been created in the config folder. Please fill it and run the install script again.";
            return true;
        }

        $options = [
            'filePath' => APP_PATH . "/config/install.ini",
            'adapter' => 'ini',
        ];

        $config = Factory::load($options);
        if ($config->path('admin.firstname') === null || $config->path('admin.lastname') === null || $config->path('admin.email') === null || $config->path('admin.password') === null) {
            echo "[ERROR] Please fill the install.ini file in the config folder and run the install script again.";
            return false;
        } else {
            $firstname = $config->path('admin.firstname');
            $lastname = $config->path('admin.lastname');
            $email = $config->path('admin.email');
            $username = $config->path('admin.username');
            $password = $config->path('admin.password');
            $validation = new Validation();
            $validation->add('email', new Validation\Validator\Email(['message' => 'The e-mail is not valid']));
            $validation->add('firstname', new Validation\Validator\PresenceOf(['message' => 'You must provide a firstname']));
            $validation->add('lastname', new Validation\Validator\PresenceOf(['message' => 'You must provide a lastname']));
            $validation->add('password', new Validation\Validator\PresenceOf(['message' => 'You must provide a password']));
            $dataToValidate = [
                'email' => $email,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'password' => $password
            ];
            $messages = $validation->validate($dataToValidate);
            if ($messages->count()) {
                echo "[ERROR] The install.ini file contains incorrect values:", PHP_EOL;
                foreach ($messages as $message) {
                    echo $message->getMessage(), PHP_EOL;
                }
                return false;
            }
            $member = new Member();
            $member->firstname = $firstname;
            $member->lastname = $lastname;
            $member->email = $email;
            $member->username = empty($username) ? null : $username;
            $member->password = $this->security->hash($password);
            $member->role = 'admin';
            $member->status = Member::STATUS_ACTIVE;
            $member->dateCreated = Tools::now();
            $member->save();
            echo "The administrator account has been created.", PHP_EOL;
            if (!unlink(APP_PATH . "/config/install.ini")) {
                echo "[ERROR] Cannot delete install.ini file in config folder. Please remove it manually to avoid security issues.", PHP_EOL;
            }
            $this->createOptions();
            $this->createSpecialPages();
            echo "Installation done! You can now open your fresh mCMS website with your favorite browser!";
        }
        return true;
    }

    private function createOptions()
    {
        if (Option::count()) {
            return false;
        }
        $this->insertOption(1, 'Site en maintenance', 'maintenance_enabled', 'true');
        $this->insertOption(2, 'Message de maintenance', 'maintenance_message', '<p>Le site est actuellement en maintenance.</p>');
        $this->insertOption(3, 'Notification active', 'notification_enabled', 'false');
        $this->insertOption(4, 'Type de notification', 'notification_type', NULL);
        $this->insertOption(5, 'Message de notification', 'notification_message', NULL);
        $this->insertOption(6, 'Inscription autorisée', 'registration_allowed', 'true');
        $this->insertOption(7, 'Commentaires autorisés', 'comments_allowed', 'true');
        $this->insertOption(8, 'Commentaires sur les pages autorisés', 'comments_pages_allowed', 'true');
        $this->insertOption(9, 'Commentaires sur les albums autorisés', 'comments_albums_allowed', 'true');
        $this->insertOption(10, 'Commentaires sur les articles autorisés', 'comments_articles_allowed', 'true');
        $this->insertOption(11, 'Commentaires maximum par jour', 'comments_maximum_per_day', '5');
        $this->insertOption(12, 'Largeur des miniatures', 'thumbnail_width', '200');
        $this->insertOption(13, 'Hauteur des miniatures', 'thumbnail_height', '200');
        $this->insertOption(14, 'Administrateur principal', 'root', '1');
        $this->insertOption(15, 'Bandeau des cookies - couleur de fond', 'cookie_consent_background_color', '#000000');
        $this->insertOption(16, 'Bandeau des cookies - couleur du texte', 'cookie_consent_text_color', '#FFFFFF');
        $this->insertOption(17, 'Bandeau des cookies - couleur de fond du bouton', 'cookie_consent_button_background_color', '#FFFF00');
        $this->insertOption(18, 'Bandeau des cookies - couleur du texte du bouton', 'cookie_consent_button_text_color', '#000000');
        $this->insertOption(19, 'Bandeau des cookies - couleur du lien', 'cookie_consent_link_color', '#0000FF');
        $this->insertOption(20, 'Bandeau des cookies - texte', 'cookie_consent_text', 'Ce site utilise des cookies pour vous assurer une meilleure navigation possible.');
        $this->insertOption(21, 'Bandeau des cookies - texte du bouton', 'cookie_consent_text_button', 'J\'ai compris');
        $this->insertOption(22, 'Bandeau des cookies - texte du lien', 'cookie_consent_text_link', 'En savoir plus');
        $this->insertOption(23, 'Google reCaptcha sitekey', 'google_recaptcha_sitekey', NULL);
        $this->insertOption(24, 'Google reCaptcha secret', 'google_recaptcha_secret', NULL);
        $this->insertOption(25, 'Google reCaptcha actif pour l\'inscription', 'google_recaptcha_enabled_for_registration', 'false');
        $this->insertOption(26, 'Google reCaptcha actif pour les commentaires', 'google_recaptcha_enabled_for_comments', 'false');
        $this->insertOption(27, 'Statut par défaut des membres', 'member_default_status', 'active');
        echo "The default options have been created.", PHP_EOL;
        return true;
    }

    private function insertOption($id, $internTitle, $slug, $content)
    {
        $option = new Option();
        $option->id = $id;
        $option->internTitle = $internTitle;
        $option->slug = $slug;
        $option->content = $content;
        $option->save();
    }

    private function createSpecialPages()
    {
        if (SpecialPage::count()) {
            return false;
        }
        $this->insertSpecialPage(1, 'Bienvenue sur mCMS', 'index', '<p>Bienvenue sur votre nouveau site mCMS !</p><p>Vous pouvez configurer votre page d\'accueil en vous rendant dans l\'administration.</p>', 'Page d\'accueil');
        $this->insertSpecialPage(2, 'Contactez-nous !', 'contact', '<p>Utilisez le formulaire ci-dessous pour nous contacter.</p>', 'Page de contact');
        $this->insertSpecialPage(3, 'Conditions Générales d\'Utilisation', 'gtu', '<h1>Conditions Générales d\'Utilisation</h1>', 'Conditions Générales d\'Utilisation');
        echo "The default pages have been created.", PHP_EOL;
        return true;
    }

    private function insertSpecialPage($id, $title, $slug, $content, $internTitle)
    {
        $page = new SpecialPage();
        $page->id = $id;
        $page->title = $title;
        $page->slug = $slug;
        $page->content = $content;
        $page->internTitle = $internTitle;
        $page->save();
    }

}
