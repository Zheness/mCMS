<?php

namespace Mcms\Modules\Frontend\Controllers;

use Mcms\Library\Tools;
use Mcms\Models\Comment;
use Mcms\Models\Page;
use Mcms\Modules\Frontend\Forms\AddPageCommentForm;

class PageController extends ControllerBase
{

    public function indexAction()
    {
        $pages = Page::find([
            'conditions' => $this->session->has('member') ? null : 'isPrivate = 0'
        ]);
        $this->view->setVar('pages', $pages);
        $this->view->setVar('activeMenu', 'pages');
    }

    public function readAction($slug = null)
    {
        if ($slug === null) {
            // 404
            exit("404");
        }
        $page = Page::findFirstBySlug($slug);
        if (!$page) {
            // 404
            exit("404");
        }
        if ($page->isPrivate && !$this->session->has("member")) {
            exit("401");
        }

        $memberConnected = $this->session->has("member");

        $formComment = new AddPageCommentForm(null, ['connected' => $memberConnected]);

        if ($this->request->isPost()) {
            if ($formComment->isValid($this->request->getPost())) {
                $content = $this->request->getPost("content");

                $comment = new Comment();
                $comment->ipAddress = $this->request->getClientAddress();
                $comment->pageId = $page->id;
                $comment->content = $content;
                $comment->dateCreated = Tools::now();

                if ($memberConnected) {
                    $member = $this->session->get("member");
                    $comment->createdBy = $member->id;
                    $comment->username = $member->getFullname();
                } else {
                    $username = $this->request->getPost("username");
                    $comment->username = $username;
                }

                $comment->save();
                $this->flashSession->success("Le commentaire a bien été enregistré.");
                $formComment->clear();
            } else {
                $this->generateFlashSessionErrorForm($formComment);
            }
        }

        $this->view->setVar('page', $page);
        $this->view->setVar('metaTitle', $page->title);
        $this->view->setVar('formComment', $formComment);
    }

}

