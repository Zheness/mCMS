<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Library\Tools;
use Mcms\Models\SpecialPage;
use Mcms\Modules\Admin\Forms\EditSpecialPageForm;

/**
 * Class SpecialPageController
 * @Private
 * @Admin
 */
class SpecialPageController extends ControllerBase
{
    public function indexAction()
    {
        $this->view->setVar('pages', SpecialPage::find());
    }

    public function editAction($id = 0)
    {
        $page = SpecialPage::findFirst($id);
        if (!$page) {
            $this->flashSession->error("La page séléctionnée n'existe pas.");
            $this->dispatcher->forward(
                [
                    "controller" => "specialPage",
                    "action" => "index",
                ]
            );
            return false;
        }

        $this->addAssetsTinyMce();
        $form = new EditSpecialPageForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $title = $this->request->getPost("title");
                $content = $this->request->getPost("content");

                $page->title = $title;
                $page->content = $content;
                $page->dateUpdated = Tools::now();
                $page->updatedBy = $this->session->get('member')->id;
                $page->save();
                $this->addLog('member', 'Page spéciale modifiée par le membre #' . $this->session->get('member')->id, $this->session->get('member')->getFullname(), $page->id, 'Page: ' . $page->internTitle);
                $this->addLog('specialPage', 'Page #' . $page->id . ' modifiée', $this->session->get('member')->getFullname(), $this->session->get('member')->id, 'Page: ' . $page->internTitle);
                $this->flashSession->success("La page a bien été enregistrée.");
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        } else {
            $form->get("title")->setDefault($page->title);
            $form->get("content")->setDefault($page->content);
        }
        $this->view->setVar("page", $page);
        $this->view->setVar("form", $form);
        return true;
    }
}

