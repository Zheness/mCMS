<?php
namespace Mcms\Modules\Admin\Controllers;

use Mcms\Library\Tools;
use Mcms\Models\Log;
use Mcms\Models\Message;
use Mcms\Models\Option;
use Mcms\Modules\Admin\Forms\FormBase;
use Phalcon\Mvc\Controller;
use Phalcon\Validation\Message\Group;

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

    /**
     * Generate the html message which contains all the file upload errors
     * @param Group $messages
     */
    protected function generateFileUploadErrorMessage($messages)
    {
        $html = "<p>Le fichier envoyé comporte les erreurs suivantes:</p>";
        $html .= "<ul>";
        foreach ($messages as $message) {
            $html .= "<li><b>Fichier</b>: {$message->getMessage()}</li>";
        }
        $html .= "</ul>";
        $this->flashSession->error($html);
    }

    public function afterExecuteRoute()
    {
        $this->assets->addCss("adminFiles/css/admin-style.css");
        if (!$this->request->isAjax() && !$this->response->isSent()) {
            if (Option::findFirstBySlug('maintenance_enabled')->content == 'true' && $this->session->has('member')) {
                $this->flashSession->warning('<p>Le site est actuellement en maintenance et n\'est pas disponible au public.</p><p>Rendez-vous sur la <a href="' . $this->url->get('option/maintenance') . '">page d\'option</a> pour réactiver le site.</p>');
            }
        }
        $this->view->setVar("menu_unreadMessages", Message::count(['parentId IS NULL AND unread = 1']));
        $this->view->setVar("csrfKey", $this->security->getTokenKey());
        $this->view->setVar("csrf", $this->security->getToken());
    }

    protected function addAssetsTinyMce()
    {
        $this->assets->addJs("vendor/tinymce/tinymce/tinymce.min.js");
        $this->assets->addJs("vendor/ivan-chkv/tinymce-i18n/langs/fr_FR.js");
        $this->assets->addJs("adminFiles/js/tinymceTextareas.js");
    }

    protected function addAssetsDataTable()
    {
        $this->assets->addCss("vendor/datatables/datatables/media/css/dataTables.bootstrap.min.css");
        $this->assets->addJs("vendor/datatables/datatables/media/js/jquery.dataTables.min.js");
        $this->assets->addJs("vendor/datatables/datatables/media/js/dataTables.bootstrap.min.js");
        $this->assets->addJs("adminFiles/js/dataTables_fr.js");
    }

    /**
     * @return array
     */
    protected function generateImageValidatorOptions()
    {
        $response = [];

        if ($this->config->module->image->maxSize !== false) {
            $response['maxSize'] = $this->config->module->image->maxSize;
            $response['messageSize'] = "Le poids de l'image est trop grand (max {$this->config->module->image->maxSize})";
        }
        if ($this->config->module->image->maxResolution !== false) {
            $response['maxResolution'] = $this->config->module->image->maxResolution;
            $response['messageMaxResolution'] = "L'image est trop grande (résolution maximum {$this->config->module->image->maxResolution})";
        }
        if ($this->config->module->image->allowedTypes !== false && count($this->config->module->image->allowedTypes)) {
            $response['allowedTypes'] = (array)$this->config->module->image->allowedTypes;
            $response['messageType'] = "Le format de l'image n'est pas supporté (types autorisés: :types)";
        }
        return $response;
    }

    protected function addLog($type, $action, $username, $sourceId = null, $content = null)
    {
        $log = new Log();
        $log->type = $type;
        $log->action = "[Administration] " . $action;
        $log->username = $username;
        $log->sourcerId = $sourceId;
        $log->content = $content;
        $log->dateCreated = Tools::now();
        $log->save();
    }
}
