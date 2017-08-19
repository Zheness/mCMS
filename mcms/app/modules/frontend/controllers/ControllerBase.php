<?php
namespace Mcms\Modules\Frontend\Controllers;

use Mcms\Models\Album;
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
        $pages = Album::find([
            'conditions' => $this->session->has('member') ? null : 'isPrivate = 0',
            'order' => 'dateCreated DESC',
            'limit' => 5
        ]);
        $this->view->setVar('menu_latestAlbums', $pages);

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
    }
}
