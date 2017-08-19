<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Library\Tools;
use Mcms\Models\Option;
use Mcms\Modules\Admin\Forms\OptionCommentsForm;
use Mcms\Modules\Admin\Forms\OptionMaintenanceForm;
use Mcms\Modules\Admin\Forms\OptionNotificationForm;
use Mcms\Modules\Admin\Forms\OptionRegistrationForm;
use Mcms\Modules\Admin\Forms\OptionThumbnailsForm;

/**
 * Class OptionController
 * @Private
 * @Admin
 */
class OptionController extends ControllerBase
{
    public function indexAction()
    {
        $options = [];
        $options['maintenance_enabled'] = Option::findFirstBySlug('maintenance_enabled')->content == 'true';
        $options['notification_enabled'] = Option::findFirstBySlug('notification_enabled')->content == 'true';
        $options['registration_allowed'] = Option::findFirstBySlug('registration_allowed')->content == 'true';
        $options['comments_allowed'] = Option::findFirstBySlug('comments_allowed')->content == 'true';
        $options['comments_pages_allowed'] = Option::findFirstBySlug('comments_pages_allowed')->content == 'true';
        $options['comments_albums_allowed'] = Option::findFirstBySlug('comments_albums_allowed')->content == 'true';
        $options['comments_articles_allowed'] = Option::findFirstBySlug('comments_articles_allowed')->content == 'true';
        $options['comments_maximum_per_day'] = Option::findFirstBySlug('comments_maximum_per_day')->content;
        $options['thumbnail_dimensions'] = Option::findFirstBySlug('thumbnail_width')->content . "x" . Option::findFirstBySlug('thumbnail_height')->content;
        $this->view->setVar('options', $options);
    }

    public function maintenanceAction()
    {
        $this->assets->addCss("adminFiles/css/checkboxes-switch.css");
        $this->addAssetsTinyMce();
        $form = new OptionMaintenanceForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $content = $this->request->getPost("content");
                $enabled = $this->request->getPost("enabled") == "on" ? 1 : 0;

                $option = Option::findFirstBySlug('maintenance_enabled');
                $option->content = $enabled ? 'true' : 'false';
                $option->dateUpdated = Tools::now();
                $option->updatedBy = $this->session->get('member')->id;
                $option->save();

                $option = Option::findFirstBySlug('maintenance_message');
                $option->content = $content;
                $option->dateUpdated = Tools::now();
                $option->updatedBy = $this->session->get('member')->id;
                $option->save();

                $this->flashSession->success('La configuration a bien été enregistrée.');
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        } else {
            $optionEnabled = Option::findFirstBySlug('maintenance_enabled')->content == 'true';
            $form->get("content")->setDefault(Option::findFirstBySlug('maintenance_message')->content);
            if ($optionEnabled) {
                $form->get("enabled")->setAttribute('checked', 'checked');
            }
        }
        $this->view->setVar("form", $form);
    }

    public function notificationAction()
    {
        $this->assets->addCss("adminFiles/css/checkboxes-switch.css");
        $this->addAssetsTinyMce();
        $form = new OptionNotificationForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $content = $this->request->getPost("content");
                $type = $this->request->getPost("type");
                $enabled = $this->request->getPost("enabled") == "on" ? 1 : 0;

                $option = Option::findFirstBySlug('notification_enabled');
                $option->content = $enabled ? 'true' : 'false';
                $option->dateUpdated = Tools::now();
                $option->updatedBy = $this->session->get('member')->id;
                $option->save();

                $option = Option::findFirstBySlug('notification_message');
                $option->content = $content;
                $option->dateUpdated = Tools::now();
                $option->updatedBy = $this->session->get('member')->id;
                $option->save();

                $option = Option::findFirstBySlug('notification_type');
                $option->content = $type;
                $option->dateUpdated = Tools::now();
                $option->updatedBy = $this->session->get('member')->id;
                $option->save();

                $this->flashSession->success('La configuration a bien été enregistrée.');
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        } else {
            $optionEnabled = Option::findFirstBySlug('notification_enabled')->content == 'true';
            $form->get("content")->setDefault(Option::findFirstBySlug('notification_message')->content);
            $form->get("type")->setDefault(Option::findFirstBySlug('notification_type')->content);
            if ($optionEnabled) {
                $form->get("enabled")->setAttribute('checked', 'checked');
            }
        }
        $this->view->setVar("form", $form);
    }

    public function registrationAction()
    {
        $this->assets->addCss("adminFiles/css/checkboxes-switch.css");
        $this->addAssetsTinyMce();
        $form = new OptionRegistrationForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $enabled = $this->request->getPost("enabled") == "on" ? 1 : 0;

                $option = Option::findFirstBySlug('registration_allowed');
                $option->content = $enabled ? 'true' : 'false';
                $option->dateUpdated = Tools::now();
                $option->updatedBy = $this->session->get('member')->id;
                $option->save();

                $this->flashSession->success('La configuration a bien été enregistrée.');
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        } else {
            $optionEnabled = Option::findFirstBySlug('registration_allowed')->content == 'true';
            if ($optionEnabled) {
                $form->get("enabled")->setAttribute('checked', 'checked');
            }
        }
        $this->view->setVar("form", $form);
    }

    public function commentsAction()
    {
        $this->assets->addCss("adminFiles/css/checkboxes-switch.css");
        $this->addAssetsTinyMce();
        $form = new OptionCommentsForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $enabled = $this->request->getPost("enabled") == "on" ? 1 : 0;
                $pagesEnabled = $this->request->getPost("pagesEnabled") == "on" ? 1 : 0;
                $albumsEnabled = $this->request->getPost("albumsEnabled") == "on" ? 1 : 0;
                $articlesEnabled = $this->request->getPost("articlesEnabled") == "on" ? 1 : 0;
                $maximumCommentsPerDay = (int)$this->request->getPost("maximumCommentsPerDay");
                if ($maximumCommentsPerDay < -1) {
                    $maximumCommentsPerDay = -1;
                }

                $option = Option::findFirstBySlug('comments_allowed');
                $option->content = $enabled ? 'true' : 'false';
                $option->dateUpdated = Tools::now();
                $option->updatedBy = $this->session->get('member')->id;
                $option->save();

                $option = Option::findFirstBySlug('comments_pages_allowed');
                $option->content = $pagesEnabled ? 'true' : 'false';
                $option->dateUpdated = Tools::now();
                $option->updatedBy = $this->session->get('member')->id;
                $option->save();

                $option = Option::findFirstBySlug('comments_albums_allowed');
                $option->content = $albumsEnabled ? 'true' : 'false';
                $option->dateUpdated = Tools::now();
                $option->updatedBy = $this->session->get('member')->id;
                $option->save();

                $option = Option::findFirstBySlug('comments_articles_allowed');
                $option->content = $articlesEnabled ? 'true' : 'false';
                $option->dateUpdated = Tools::now();
                $option->updatedBy = $this->session->get('member')->id;
                $option->save();

                $option = Option::findFirstBySlug('comments_maximum_per_day');
                $option->content = $maximumCommentsPerDay;
                $option->dateUpdated = Tools::now();
                $option->updatedBy = $this->session->get('member')->id;
                $option->save();

                $this->flashSession->success('La configuration a bien été enregistrée.');
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        } else {
            $form->get("maximumCommentsPerDay")->setDefault(Option::findFirstBySlug('comments_maximum_per_day')->content);
            $optionEnabled = Option::findFirstBySlug('comments_allowed')->content == 'true';
            if ($optionEnabled) {
                $form->get("enabled")->setAttribute('checked', 'checked');
            }
            $optionEnabled = Option::findFirstBySlug('comments_pages_allowed')->content == 'true';
            if ($optionEnabled) {
                $form->get("pagesEnabled")->setAttribute('checked', 'checked');
            }
            $optionEnabled = Option::findFirstBySlug('comments_albums_allowed')->content == 'true';
            if ($optionEnabled) {
                $form->get("albumsEnabled")->setAttribute('checked', 'checked');
            }
            $optionEnabled = Option::findFirstBySlug('comments_articles_allowed')->content == 'true';
            if ($optionEnabled) {
                $form->get("articlesEnabled")->setAttribute('checked', 'checked');
            }
        }
        $this->view->setVar("form", $form);
    }

    public function thumbnailsAction()
    {
        $form = new OptionThumbnailsForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $width = (int)$this->request->getPost("width");
                $height = (int)$this->request->getPost("height");
                $width = $width <= 0 ? 1 : $width;
                $height = $height <= 0 ? 1 : $height;

                $option = Option::findFirstBySlug('thumbnail_width');
                $option->content = $width;
                $option->dateUpdated = Tools::now();
                $option->updatedBy = $this->session->get('member')->id;
                $option->save();

                $option = Option::findFirstBySlug('thumbnail_height');
                $option->content = $height;
                $option->dateUpdated = Tools::now();
                $option->updatedBy = $this->session->get('member')->id;
                $option->save();

                $this->flashSession->success('La configuration a bien été enregistrée.');
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        } else {
            $form->get("width")->setDefault(Option::findFirstBySlug('thumbnail_width')->content);
            $form->get("height")->setDefault(Option::findFirstBySlug('thumbnail_height')->content);
        }
        $this->view->setVar("form", $form);
    }
}

