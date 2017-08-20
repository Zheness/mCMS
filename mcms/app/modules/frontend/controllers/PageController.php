<?php

namespace Mcms\Modules\Frontend\Controllers;

use Mcms\Library\Tools;
use Mcms\Models\Comment;
use Mcms\Models\Option;
use Mcms\Models\Page;
use Mcms\Modules\Frontend\Forms\AddCommentForm;
use Phalcon\Filter;

class PageController extends ControllerBase
{

    public function indexAction()
    {
        $pages = Page::find([
            'conditions' => $this->session->has('member') ? null : 'isPrivate = 0',
            'order' => 'dateCreated DESC'
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

        $formComment = new AddCommentForm(null, ['connected' => $memberConnected]);

        $commentsOpen = Option::findFirstBySlug('comments_allowed')->content == 'true' && Option::findFirstBySlug('comments_pages_allowed')->content == 'true' && $page->commentsOpen;

        if ($this->request->isPost()) {
            if ($commentsOpen) {
                if ($formComment->isValid($this->request->getPost())) {
                    $canPostComment = true;
                    if (!$memberConnected) {
                        $maximumCommentsPerDay = (int)Option::findFirstBySlug('comments_maximum_per_day')->content;
                        if ($maximumCommentsPerDay == 0) {
                            $canPostComment = false;
                            $this->flashSession->error("Seuls les membres peuvent poster des commentaires.");
                        } else if ($maximumCommentsPerDay == -1) {
                            $canPostComment = true;
                        } else {
                            $nbCommentsForUser = Comment::count(['ipAddress LIKE :ipAddress: AND DATE(dateCreated) LIKE :today: AND createdBy IS NULL', 'bind' => [
                                'ipAddress' => $this->request->getClientAddress(),
                                'today' => date('Y-m-d')
                            ]]);
                            if ($nbCommentsForUser < $maximumCommentsPerDay) {
                                $canPostComment = true;
                            } else {
                                $canPostComment = false;
                                $this->flashSession->error("Vous avez atteint la limite maximum des commentaires autorisés par jour.");
                            }
                        }
                    }

                    if ($canPostComment) {
                        $content = $this->request->getPost("content", [Filter::FILTER_SPECIAL_CHARS]);

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
                            $username = $this->request->getPost("username", [Filter::FILTER_SPECIAL_CHARS]);
                            $comment->username = $username;
                        }

                        $comment->save();
                        $this->flashSession->success("Le commentaire a bien été enregistré.");
                        $formComment->clear();
                    }
                } else {
                    $this->generateFlashSessionErrorForm($formComment);
                }
            } else {
                $this->flashSession->error("Les commentaires sont désactivés.");
            }
        }

        $this->view->setVar('page', $page);
        $this->view->setVar('commentsOpen', $commentsOpen);
        $this->view->setVar('metaTitle', $page->title);
        $this->view->setVar('formComment', $formComment);
    }

}

