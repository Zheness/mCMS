<?php

namespace Mcms\Modules\Frontend\Controllers;

use Mailgun\Mailgun;
use Mcms\Library\Tools;
use Mcms\Models\Member;
use Mcms\Models\Option;
use Mcms\Models\SpecialPage;
use Mcms\Modules\Frontend\Forms\LoginForm;
use Mcms\Modules\Frontend\Forms\SignupForm;
use Phalcon\Filter;
use Phalcon\Mvc\View;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        $this->view->setVar('activeMenu', 'homepage');
        $page = SpecialPage::findFirstBySlug('index');
        $this->view->setVar('metaTitle', $page->title);
        $this->view->setVar('content', $page->content);
    }

    public function loginAction()
    {
        if ($this->session->has("member")) {
            $this->response->redirect("");
            $this->response->send();
            return false;
        }
        $form = new LoginForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $email = $this->request->getPost("email");
                $password = $this->request->getPost("password");
                $member = Member::findFirstByEmail($email);
                if ($member) {
                    if ($this->security->checkHash($password, $member->password)) {
                        $member->token = null;
                        $member->save();
                        switch ($member->status) {
                            case Member::STATUS_ACTIVE:
                                $this->session->set("member", $member);
                                $this->addLog('member', 'Connexion à l\'espace membre réussie', $member->getFullname(), $member->id);
                                $this->flashSession->success("Vous êtes maintenant connecté à l'espace membre.");
                                break;
                            case Member::STATUS_PENDING:
                                $this->addLog('member', 'Connexion annulée, le compte est en attente de validation', $member->getFullname(), $member->id, 'Addresse IP: ' . $this->request->getClientAddress());
                                $this->flashSession->warning("Connexion échouée, votre compte est toujours en attente de validation.");
                                break;
                            case Member::STATUS_BLOCKED:
                                $this->addLog('member', 'Connexion annulée, le compte est bloqué', $member->getFullname(), $member->id, 'Addresse IP: ' . $this->request->getClientAddress());
                                $this->flashSession->error("Connexion impossible, votre compte a été bloqué.");
                                break;
                            default:
                                $this->addLog('member', 'Statut du compte inconnu', $member->getFullname(), $member->id);
                                $this->flashSession->error("Impossible de récupérer le statut de votre compte.");
                        }
                        $this->view->disable();
                        $this->response->redirect("");
                        $this->response->send();
                        return false;
                    } else {
                        $this->addLog('member', 'Tentaive de connexion avec mot de passe erroné', $member->getFullname(), $member->id, 'Addresse IP: ' . $this->request->getClientAddress());
                        $this->flashSession->error("Aucun compte trouvé avec ces identifiants.");
                    }
                } else {
                    $this->flashSession->error("Aucun compte trouvé avec ces identifiants.");
                }
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        }
        $this->view->setVar("form", $form);
        $this->view->setVar('metaTitle', 'Connexion à l\'espace membre');
        $this->view->setVar('activeMenu', 'login');
        return true;
    }

    public function logoutAction()
    {
        if ($this->session->has("member")) {
            $member = $this->session->get('member');
            $this->addLog('member', 'Déconnexion de l\'espace membre réussie', $member->getFullname(), $member->id);
            $this->session->remove("member");
            $this->flashSession->notice("Vous êtes maintenant déconnecté.");
        }
        $this->dispatcher->forward(["controller" => "index", "action" => "index"]);
    }

    public function signupAction()
    {
        if ($this->session->has("member")) {
            $this->response->redirect("");
            $this->response->send();
            return false;
        }
        if (Option::findFirstBySlug('registration_allowed')->content != 'true') {
            $this->flashSession->error("L'inscription est désactivée sur ce site.");
            $this->response->redirect("");
            $this->response->send();
            return false;
        }
        $this->view->setVar('reCaptchaEnabled', false);
        if (Option::findFirstBySlug('google_recaptcha_enabled_for_registration')->content == 'true') {
            $this->assets->addJs("https://www.google.com/recaptcha/api.js", false);
            $this->view->setVar('reCaptchaEnabled', true);
            $this->view->setVar('reCaptchaKey', Option::findFirstBySlug('google_recaptcha_sitekey')->content);
        }
        $form = new SignupForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $email = $this->request->getPost("email");
                $password = $this->request->getPost("password");
                $firstname = $this->request->getPost("firstname", [Filter::FILTER_SPECIAL_CHARS]);
                $lastname = $this->request->getPost("lastname", [Filter::FILTER_SPECIAL_CHARS]);
                $member = Member::findFirstByEmail($email);
                if (!$member) {
                    $defaultStatus = Option::findFirstBySlug('member_default_status')->content;
                    $member = new Member();
                    $member->firstname = $firstname;
                    $member->lastname = $lastname;
                    $member->email = $email;
                    $member->password = $this->security->hash($password);
                    $member->dateCreated = Tools::now();
                    $member->role = 'member';
                    $member->status = $defaultStatus;
                    $member->save();
                    $this->addLog('member', 'Inscription à l\'espace membre réussie', $member->getFullname(), $member->id);
                    if ($defaultStatus == Member::STATUS_ACTIVE) {
                        $this->session->set("member", $member);
                        $this->flashSession->success("Inscription réussie ! Vous êtes maintenant connecté à l'espace membre.");
                    } else {
                        $this->flashSession->success("Votre compte a bien été créé.");
                    }
                    $this->view->disable();
                    $this->response->redirect("");
                    $this->response->send();
                    return false;
                } else {
                    $this->flashSession->error("Cette addresse email est déjà utilisée.");
                }
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        }
        $this->view->setVar("form", $form);
        $this->view->setVar('metaTitle', 'Inscription à l\'espace membre');
        $this->view->setVar('activeMenu', 'signup');
        return true;
    }

    public function maintenanceAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('message', Option::findFirstBySlug('maintenance_message')->content);
    }

    public function gtuAction()
    {
        $page = SpecialPage::findFirstBySlug('gtu');
        $this->view->setVar('metaTitle', $page->title);
        $this->view->setVar('content', $page->content);
    }

}

