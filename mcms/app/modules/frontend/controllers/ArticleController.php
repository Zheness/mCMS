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
        $articleQueryCondition = 'datePublication < NOW()';
        if (!$this->session->has('member')) {
            $articleQueryCondition .= " AND isPrivate = 0";
        }
        $articles = Article::find([
            'conditions' => $articleQueryCondition,
            'order' => 'datePublication DESC'
        ]);
        $this->view->setVar('articles', $articles);
        $this->view->setVar('activeMenu', 'articles');
    }

    public function readAction($year = null, $month = null, $day = null, $slug = null)
    {
        if ($year === null && $month === null && $day == null && $slug === null) {
            $this->dispatcher->forward(
                [
                    'controller' => 'error',
                    'action' => 'error404',
                ]
            );
            $this->response->setStatusCode(404);
            return false;
        }
        /** @var Article $article */
        $article = Article::findFirstBySlug($slug);
        if (!$article) {
            $this->dispatcher->forward(
                [
                    'controller' => 'error',
                    'action' => 'error404',
                ]
            );
            $this->response->setStatusCode(404);
            return false;
        }
        $dateFr = "{$day}/{$month}/{$year}";
        if ($article->datePublicationToFr() != $dateFr) {
            $this->dispatcher->forward(
                [
                    'controller' => 'error',
                    'action' => 'error404',
                ]
            );
            $this->response->setStatusCode(404);
            return false;
        }
        if ($article->isPrivate && !$this->session->has("member")) {
            $this->dispatcher->forward(
                [
                    'controller' => 'error',
                    'action' => 'error401',
                ]
            );
            $this->response->setStatusCode(401);
            return false;
        }
        if (!$article->datePublicationReached()) {
            if (!$this->session->has("member") || $this->session->get("member")->role != 'admin') {
                $this->dispatcher->forward(
                    [
                        'controller' => 'error',
                        'action' => 'error401',
                    ]
                );
                $this->response->setStatusCode(401);
                return false;
            }
            $this->flashSession->warning("Cet article n'est toujours pas publié. Seuls les administrateur peuvent la voir.");
        }

        $this->view->setVar('reCaptchaEnabled', false);
        if (Option::findFirstBySlug('google_recaptcha_enabled_for_comments')->content == 'true') {
            $this->assets->addJs("https://www.google.com/recaptcha/api.js", false);
            $this->view->setVar('reCaptchaEnabled', true);
            $this->view->setVar('reCaptchaKey', Option::findFirstBySlug('google_recaptcha_sitekey')->content);
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

                    if ($memberConnected) {
                        $member = $this->session->get('member');
                        $this->addLog('member', 'Commentaire #' . $comment->id . ' ajouté sur l\'article #' . $article->id, $member->getFullname(), $member->id, 'Article: ' . $article->title);
                        $this->addLog('comment', 'Commentaire ajouté par le membre #' . $member->id . ' sur l\'article #' . $article->id, $member->getFullname(), $comment->id, 'Article: ' . $article->title);
                        $this->addLog('article', 'Commentaire ajouté par le membre #' . $member->id, $member->getFullname(), $article->id, 'Article: ' . $article->title);
                    } else {
                        $this->addLog('comment', 'Commentaire ajouté sur l\'article #' . $article->id, 'Anonyme', $comment->id, 'Article: ' . $article->title);
                        $this->addLog('article', 'Commentaire ajouté', 'Anonyme', $article->id, 'Article: ' . $article->title);
                    }
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

    public function listAction($year = null, $month = null)
    {
        if ($year === null && $month === null) {
            $this->dispatcher->forward(
                [
                    'controller' => 'error',
                    'action' => 'error404',
                ]
            );
            $this->response->setStatusCode(404);
            return false;
        }
        $this->assets->addCss('css/article.css');
        $articleQueryCondition = 'datePublication < NOW()';
        if (!$this->session->has('member')) {
            $articleQueryCondition .= " AND isPrivate = 0";
        }
        if ((int)$year <= 0 || (int)$year >= 9999) {
            $this->dispatcher->forward(
                [
                    'controller' => 'error',
                    'action' => 'error404',
                ]
            );
            $this->response->setStatusCode(404);
            return false;
        }
        if ($month !== null) {
            if (!((int)$month > 0 && (int)$month <= 12)) {
                $this->dispatcher->forward(
                    [
                        'controller' => 'error',
                        'action' => 'error404',
                    ]
                );
                $this->response->setStatusCode(404);
                return false;
            }
        }
        $monthsStr = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        if ($month == null) {
            $articlesCount = [
                [
                    "name" => "Janvier",
                    "month" => 1,
                    "count" => 0,
                ],
                [
                    "name" => "Février",
                    "month" => 2,
                    "count" => 0,
                ],
                [
                    "name" => "Mars",
                    "month" => 3,
                    "count" => 0,
                ],
                [
                    "name" => "Avril",
                    "month" => 4,
                    "count" => 0,
                ],
                [
                    "name" => "Mai",
                    "month" => 5,
                    "count" => 0,
                ],
                [
                    "name" => "Juin",
                    "month" => 6,
                    "count" => 0,
                ],
                [
                    "name" => "Juillet",
                    "month" => 7,
                    "count" => 0,
                ],
                [
                    "name" => "Août",
                    "month" => 8,
                    "count" => 0,
                ],
                [
                    "name" => "Septembre",
                    "month" => 9,
                    "count" => 0,
                ],
                [
                    "name" => "Octobre",
                    "month" => "10",
                    "count" => 0,
                ],
                [
                    "name" => "Novembre",
                    "month" => "11",
                    "count" => 0,
                ],
                [
                    "name" => "Décembre",
                    "month" => "12",
                    "count" => 0,
                ],
            ];
            foreach ($articlesCount as $key => $item) {
                $query = $articleQueryCondition;
                $query .= " AND (YEAR(datePublication) = '{$year}' AND MONTH(datePublication) = '{$item['month']}')";
                $articlesCount[$key]['count'] = Article::count($query);
                $articlesCount[$key]['year'] = $year;
            }
            $articles = $articlesCount;
            $query = $articleQueryCondition;
            $previousYear = $year - 1;
            $query .= " AND YEAR(datePublication) = '{$previousYear}'";
            $previousYearArticles = Article::count($query);
            $monthStr = null;
        } else {
            $month = (int)$month;
            $previousYearArticles = 0;
            $query = $articleQueryCondition;
            $query .= " AND (YEAR(datePublication) = '{$year}' AND MONTH(datePublication) = '{$month}')";
            $articles = Article::find([
                'conditions' => $query,
                'order' => 'datePublication DESC'
            ]);
            $this->view->pick('article/month');
        }
        $this->view->setVar('articles', $articles);
        $this->view->setVar('year', $year);
        $this->view->setVar('month', $month);
        $this->view->setVar('monthsStr', $monthsStr);
        $this->view->setVar('previousYearArticles', $previousYearArticles);
        $this->view->setVar('activeMenu', 'articles');
    }

}

