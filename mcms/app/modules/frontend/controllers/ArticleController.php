<?php

namespace Mcms\Modules\Frontend\Controllers;

use Mcms\Library\Tools;
use Mcms\Models\Album;
use Mcms\Models\Article;
use Mcms\Models\Comment;
use Mcms\Models\Option;
use Mcms\Modules\Frontend\Forms\AddCommentForm;
use Phalcon\Filter;

class ArticleController extends ControllerBase
{

    public function indexAction()
    {
        $albums = Album::find([
            'conditions' => $this->session->has('member') ? null : 'isPrivate = 0'
        ]);
        $this->view->setVar('albums', $albums);
        $this->view->setVar('activeMenu', 'albums');
    }

    public function readAction($year = null, $month = null, $day = null, $slug = null)
    {
        if ($year === null && $month === null && $day == null && $slug === null) {
            // 404
            exit("404");
        }
        /** @var Article $article */
        $article = Article::findFirstBySlug($slug);
        if (!$article) {
            // 404
            exit("404");
        }
        $dateFr = "{$day}/{$month}/{$year}";
        if ($article->datePublicationToFr() != $dateFr) {
            // 404
            exit("404");
        }
        if ($article->isPrivate && !$this->session->has("member")) {
            exit("401");
        }
        if (!$article->datePublicationReached()) {
            if (!$this->session->has("member") || $this->session->get("member")->role != 'admin') {
                exit("401");
            }
            $this->flashSession->warning("Cet article n'est toujours pas publié. Seuls les administrateur peuvent la voir.");
        }

        $memberConnected = $this->session->has("member");

        $formComment = new AddCommentForm(null, ['connected' => $memberConnected]);

        $commentsOpen = Option::findFirstBySlug('comments_allowed')->content == 'true' && Option::findFirstBySlug('comments_articles_allowed')->content == 'true' && $article->commentsOpen;

        if ($this->request->isPost()) {
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
                    $comment->articleId = $article->id;
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
        }

        $this->view->setVar('article', $article);
        $this->view->setVar('commentsOpen', $commentsOpen);
        $this->view->setVar('metaTitle', $article->title);
        $this->view->setVar('formComment', $formComment);
    }

}

