<?php
namespace Mcms\Modules\Frontend\Controllers;

use Mcms\Library\Tools;
use Mcms\Models\Album;
use Mcms\Models\Article;
use Mcms\Models\Log;
use Mcms\Models\Option;
use Mcms\Models\Page;
use Mcms\Modules\Frontend\Forms\FormBase;
use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    /**
     * Generate the html message which contains all the form errors
     * @param FormBase $form
     */
    protected function generateFlashSessionErrorForm($form)
    {
        if ($form->hasCsrfCheck() && !$form->isCsrfValid()) {
            $html = "<p>Le formulaire a expiré, merci de ré-essayer.</p>";
        } else {
            $html = "<p>Le formulaire reçu comporte les erreurs suivantes:</p>";
            $html .= "<ul>";
            foreach ($form->getMessages() as $error) {
                $field = $form->get($error->getField());
                $html .= "<li><b>{$field->getLabel()}</b>: {$error->getMessage()}</li>";
            }
            $html .= "</ul>";
        }
        $this->flashSession->error($html);
    }

    public function beforeExecuteRoute()
    {
        if (Option::findFirstBySlug('maintenance_enabled')->content == 'true') {
            $url = $this->dispatcher->getControllerName() . "/" . $this->dispatcher->getActionName();
            if ($url != 'index/maintenance') {
                $this->dispatcher->forward(["controller" => "index", "action" => "maintenance"]);
                $this->response->setStatusCode(503);
                return false;
            }
        }
        return true;
    }

    public function afterExecuteRoute()
    {
        $this->view->setVar("csrfKey", $this->security->getTokenKey());
        $this->view->setVar("csrf", $this->security->getToken());
        /*
         * Latest pages
         */
        $pages = Page::find([
            'conditions' => $this->session->has('member') ? null : 'isPrivate = 0',
            'order' => 'dateCreated DESC',
            'limit' => 5
        ]);
        $this->view->setVar('menu_latestPages', $pages);
        /*
         * Latest albums
         */
        $albums = Album::find([
            'conditions' => $this->session->has('member') ? null : 'isPrivate = 0',
            'order' => 'dateCreated DESC',
            'limit' => 5
        ]);
        $this->view->setVar('menu_latestAlbums', $albums);
        /*
         * Latest articles
         */
        $articleQueryCondition = 'datePublication < NOW()';
        if (!$this->session->has('member')) {
            $articleQueryCondition .= " AND isPrivate = 0";
        }
        $articles = Article::find([
            'conditions' => $articleQueryCondition,
            'order' => 'dateCreated DESC',
            'limit' => 5
        ]);
        $this->view->setVar('menu_latestArticles', $articles);

        $this->view->setVar('registrationAllowed', Option::findFirstBySlug('registration_allowed')->content == 'true');

        if (Option::findFirstBySlug('notification_enabled')->content == 'true') {
            $type = Option::findFirstBySlug('notification_type')->content;
            $message = Option::findFirstBySlug('notification_message')->content;
            if ($type == 'success') {
                $this->flashSession->success($message);
            } elseif ($type == 'danger') {
                $this->flashSession->error($message);
            } elseif ($type == 'warning') {
                $this->flashSession->warning($message);
            } else {
                $this->flashSession->notice($message);
            }
        }

        $cookieConsent = [
            'text' => Option::findFirstBySlug('cookie_consent_text')->content,
            'textButton' => Option::findFirstBySlug('cookie_consent_text_button')->content,
            'textLink' => Option::findFirstBySlug('cookie_consent_text_link')->content,
            'backgroundColor' => Option::findFirstBySlug('cookie_consent_background_color')->content,
            'backgroundColorButton' => Option::findFirstBySlug('cookie_consent_button_background_color')->content,
            'textColor' => Option::findFirstBySlug('cookie_consent_text_color')->content,
            'textColorButton' => Option::findFirstBySlug('cookie_consent_button_text_color')->content,
            'textColorLink' => Option::findFirstBySlug('cookie_consent_link_color')->content,
        ];
        $this->view->setVar('cookieConsent', $cookieConsent);
    }

    protected function addLog($type, $action, $username, $sourceId = null, $content = null)
    {
        $log = new Log();
        $log->type = $type;
        $log->action = $action;
        $log->username = $username;
        $log->sourcerId = $sourceId;
        $log->content = $content;
        $log->dateCreated = Tools::now();
        $log->save();
    }
}
