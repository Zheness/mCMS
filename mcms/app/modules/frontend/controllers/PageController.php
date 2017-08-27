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
            $this->dispatcher->forward(
                [
                    'controller' => 'error',
                    'action' => 'error404',
                ]
            );
            $this->response->setStatusCode(404);
            return false;
        }
        $page = Page::findFirstBySlug($slug);
        if (!$page) {
            $this->dispatcher->forward(
                [
                    'controller' => 'error',
                    'action' => 'error404',
                ]
            );
            $this->response->setStatusCode(404);
            return false;
        }
        if ($page->isPrivate && !$this->session->has("member")) {
            $this->dispatcher->forward(
                [
                    'controller' => 'error',
                    'action' => 'error401',
                ]
            );
            $this->response->setStatusCode(401);
            return false;
        }

        $this->view->setVar('reCaptchaEnabled', false);
        if (Option::findFirstBySlug('google_recaptcha_enabled_for_comments')->content == 'true') {
            $this->assets->addJs("https://www.google.com/recaptcha/api.js", false);
            $this->view->setVar('reCaptchaEnabled', true);
            $this->view->setVar('reCaptchaKey', Option::findFirstBySlug('google_recaptcha_sitekey')->content);
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

                        if ($memberConnected) {
                            $member = $this->session->get('member');
                            $this->addLog('member', 'Commentaire #' . $comment->id . ' ajouté sur la page #' . $page->id, $member->getFullname(), $member->id, 'Page: ' . $page->title);
                            $this->addLog('comment', 'Commentaire ajouté par le membre #' . $member->id . ' sur la page #' . $page->id, $member->getFullname(), $comment->id, 'Page: ' . $page->title);
                            $this->addLog('page', 'Commentaire ajouté par le membre #' . $member->id, $member->getFullname(), $page->id, 'Page: ' . $page->title);
                        } else {
                            $this->addLog('comment', 'Commentaire ajouté sur la page #' . $page->id, 'Anonyme', $comment->id, 'Page: ' . $page->title);
                            $this->addLog('page', 'Commentaire ajouté', 'Anonyme', $page->id, 'Page: ' . $page->title);
                        }
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

