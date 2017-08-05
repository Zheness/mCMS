<?php
namespace Mcms\Modules\Admin\Controllers;

use Mcms\Modules\Admin\Forms\FormBase;
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

    public function afterExecuteRoute()
    {
        $this->view->setVar("csrfKey", $this->security->getTokenKey());
        $this->view->setVar("csrf", $this->security->getToken());
    }
}
