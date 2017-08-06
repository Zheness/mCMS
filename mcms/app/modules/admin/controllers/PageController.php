<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Models\Page;
use Mcms\Modules\Admin\Forms\AddPageForm;
use Mcms\Library\Tools;
use Phalcon\Utils\Slug;

/**
 * Class PageController
 * @Private
 */
class PageController extends ControllerBase
{
    /**
     * List of the pages
     */
    public function indexAction()
    {
        $this->addAssetsDataTable();
        $this->assets->addJs("adminFiles/js/page.js");
    }

    /**
     * Add a page
     */
    public function addAction()
    {
        $this->assets->addCss("adminFiles/css/checkboxes-switch.css");
        $this->addAssetsTinyMce();
        $form = new AddPageForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $title = $this->request->getPost("title");
                $content = $this->request->getPost("content");
                $slug = $this->request->getPost("slug");
                if (empty($slug)) {
                    $slug = Slug::generate($title);
                    $_POST['slug'] = $slug; // Temporary hack to display the url in the field form in case of errors
                }
                $commentsOpen = $this->request->getPost("commentsOpen") == "on" ? 1 : 0;
                $isPrivate = $this->request->getPost("isPrivate") == "on" ? 1 : 0;

                $page = Page::findFirstBySlug($slug);
                if (!$page) {
                    $page = new Page();
                    $page->title = $title;
                    $page->slug = $slug;
                    $page->content = $content;
                    $page->commentsOpen = $commentsOpen;
                    $page->isPrivate = $isPrivate;
                    $page->dateCreated = Tools::now();
                    $page->createdBy = $this->session->get('member')->id;
                    $page->save();
                    $this->flashSession->success("La page a bien été enregistrée.");
                    $form->clear();
                } else {
                    $this->flashSession->error("Une page existe déjà avec cette url.");
                }
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        }
        $this->view->setVar("form", $form);
    }

    /**
     * Edit a page
     * @param int $id
     * @return bool
     */
    public function editAction($id = 0)
    {
        $page = Page::findFirst($id);
        if (!$page) {
            $this->flashSession->error("La page séléctionnée n'existe pas.");
            $this->dispatcher->forward(
                [
                    "controller" => "page",
                    "action" => "index",
                ]
            );
            return false;
        }

        $this->assets->addCss("adminFiles/css/checkboxes-switch.css");
        $this->addAssetsTinyMce();
        $form = new AddPageForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $title = $this->request->getPost("title");
                $content = $this->request->getPost("content");
                $slug = $this->request->getPost("slug");
                if (empty($slug)) {
                    $slug = Slug::generate($title);
                    $_POST['slug'] = $slug; // Temporary hack to display the url in the field form in case of errors
                }
                $commentsOpen = $this->request->getPost("commentsOpen") == "on" ? 1 : 0;
                $isPrivate = $this->request->getPost("isPrivate") == "on" ? 1 : 0;

                $pageWithSlug = Page::findFirstBySlug($slug);
                if (!$pageWithSlug || $pageWithSlug->id == $page->id) {
                    $page->title = $title;
                    $page->slug = $slug;
                    $page->content = $content;
                    $page->commentsOpen = $commentsOpen;
                    $page->isPrivate = $isPrivate;
                    $page->dateUpdated = Tools::now();
                    $page->updatedBy = $this->session->get('member')->id;
                    $page->save();
                    $this->flashSession->success("La page a bien été enregistrée.");
                } else {
                    $this->flashSession->error("Une page existe déjà avec cette url.");
                }
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        } else {
            $form->get("title")->setDefault($page->title);
            $form->get("slug")->setDefault($page->slug);
            $form->get("content")->setDefault($page->content);
            if ($page->commentsOpen) {
                $form->get("commentsOpen")->setAttribute('checked', 'checked');
            }
            if ($page->isPrivate) {
                $form->get("isPrivate")->setAttribute('checked', 'checked');
            }
        }
        $this->view->setVar("page", $page);
        $this->view->setVar("form", $form);
        return true;
    }
}

