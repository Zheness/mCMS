<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Models\Page;
use Mcms\Modules\Admin\Forms\AddPageForm;
use Mcms\Library\Tools;
use Phalcon\Utils\Slug;

/**
 * Class PageController
 * @package Msites\Modules\Admin\Controllers
 * @Private
 */
class PageController extends ControllerBase
{
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
                $commentsOpen = $this->request->getPost("commentsOpen") == "on";
                $isPrivate = $this->request->getPost("isPrivate") == "on";

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
}

