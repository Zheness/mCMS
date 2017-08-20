<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Models\Article;
use Mcms\Modules\Admin\Forms\AddArticleForm;
use Mcms\Library\Tools;
use Mcms\Modules\Admin\Forms\DeleteArticleForm;
use Phalcon\Utils\Slug;

/**
 * Class ArticleController
 * @Private
 * @Admin
 */
class ArticleController extends ControllerBase
{
    public function indexAction()
    {
        $this->addAssetsDataTable();
        $this->assets->addJs("adminFiles/js/article.js");
    }

    public function addAction()
    {
        $this->assets->addCss("adminFiles/css/checkboxes-switch.css");
        $this->assets->addCss("vendor/eternicode/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css");
        $this->assets->addJs("vendor/eternicode/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js");
        $this->assets->addJs("vendor/eternicode/bootstrap-datepicker/dist/locales/bootstrap-datepicker.fr.min.js");
        $this->assets->addJs("adminFiles/js/article.js");
        $this->addAssetsTinyMce();
        $form = new AddArticleForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $title = $this->request->getPost("title");
                $content = $this->request->getPost("content");
                $datePublication = $this->request->getPost("datePublication");
                $slug = $this->request->getPost("slug");
                if (empty($slug)) {
                    $slug = Slug::generate($title);
                    $_POST['slug'] = $slug; // Temporary hack to display the url in the field form in case of errors
                }
                $commentsOpen = $this->request->getPost("commentsOpen") == "on" ? 1 : 0;
                $isPrivate = $this->request->getPost("isPrivate") == "on" ? 1 : 0;

                $dateExploded = explode('/', $datePublication);
                $mysqlDateFormat = "{$dateExploded[2]}-{$dateExploded[1]}-{$dateExploded[0]}";

                $article = Article::findFirstBySlug($slug);
                if (!$article) {
                    $article = new Article();
                    $article->title = $title;
                    $article->slug = $slug;
                    $article->content = $content;
                    $article->datePublication = $mysqlDateFormat;
                    $article->commentsOpen = $commentsOpen;
                    $article->isPrivate = $isPrivate;
                    $article->dateCreated = Tools::now();
                    $article->createdBy = $this->session->get('member')->id;
                    $article->save();
                    $this->flashSession->success("L'article a bien été enregistré.");
                    $form->clear();
                    $form->get("datePublication")->setDefault(date("d/m/Y"));
                } else {
                    $this->flashSession->error("Un article existe déjà avec cette url.");
                }
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        } else {
            $form->get("datePublication")->setDefault(date("d/m/Y"));
        }
        $this->view->setVar("form", $form);
    }

    public function editAction($id = 0)
    {
        $article = Article::findFirst($id);
        if (!$article) {
            $this->flashSession->error("L'article séléctionné n'existe pas.");
            $this->dispatcher->forward(
                [
                    "controller" => "article",
                    "action" => "index",
                ]
            );
            return false;
        }
        $this->assets->addCss("adminFiles/css/checkboxes-switch.css");
        $this->assets->addCss("vendor/eternicode/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css");
        $this->assets->addJs("vendor/eternicode/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js");
        $this->assets->addJs("vendor/eternicode/bootstrap-datepicker/dist/locales/bootstrap-datepicker.fr.min.js");
        $this->assets->addJs("adminFiles/js/article.js");
        $this->addAssetsTinyMce();
        $form = new AddArticleForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $title = $this->request->getPost("title");
                $content = $this->request->getPost("content");
                $datePublication = $this->request->getPost("datePublication");
                $slug = $this->request->getPost("slug");
                if (empty($slug)) {
                    $slug = Slug::generate($title);
                    $_POST['slug'] = $slug; // Temporary hack to display the url in the field form in case of errors
                }
                $commentsOpen = $this->request->getPost("commentsOpen") == "on" ? 1 : 0;
                $isPrivate = $this->request->getPost("isPrivate") == "on" ? 1 : 0;

                $dateExploded = explode('/', $datePublication);
                $mysqlDateFormat = "{$dateExploded[2]}-{$dateExploded[1]}-{$dateExploded[0]}";

                $articleWithSlug = Article::findFirstBySlug($slug);
                if (!$articleWithSlug || $articleWithSlug->id == $article->id) {
                    $article->title = $title;
                    $article->slug = $slug;
                    $article->content = $content;
                    $article->datePublication = $mysqlDateFormat;
                    $article->commentsOpen = $commentsOpen;
                    $article->isPrivate = $isPrivate;
                    $article->dateUpdated = Tools::now();
                    $article->updatedBy = $this->session->get('member')->id;
                    $article->save();
                    $this->flashSession->success("L'article a bien été enregistré.");
                } else {
                    $this->flashSession->error("Un article existe déjà avec cette url.");
                }
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        } else {
            $form->get("title")->setDefault($article->title);
            $form->get("content")->setDefault($article->content);
            $form->get("slug")->setDefault($article->slug);
            $form->get("datePublication")->setDefault($article->datePublicationToFr());
            if ($article->commentsOpen) {
                $form->get("commentsOpen")->setAttribute('checked', 'checked');
            }
            if ($article->isPrivate) {
                $form->get("isPrivate")->setAttribute('checked', 'checked');
            }
        }
        $this->view->setVar("form", $form);
        $this->view->setVar("article", $article);
        return true;
    }

    public function deleteAction($id = 0)
    {
        $article = Article::findFirst($id);
        if (!$article) {
            $this->flashSession->error("L'article séléctionné n'existe pas.");
            $this->dispatcher->forward(
                [
                    "controller" => "article",
                    "action" => "index",
                ]
            );
            return false;
        }
        $form = new DeleteArticleForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $this->flashSession->success("L'article a bien été supprimé.");
                $article->delete();
                $this->dispatcher->forward(
                    [
                        "controller" => "article",
                        "action" => "index",
                    ]
                );
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        }
        $this->view->setVar("form", $form);
        $this->view->setVar("article", $article);
        return true;
    }

    public function commentsAction($id = 0)
    {
        $article = Article::findFirst($id);
        if (!$article) {
            $this->flashSession->error("L'article séléctionné n'existe pas.");
            $this->dispatcher->forward(
                [
                    "controller" => "article",
                    "action" => "index",
                ]
            );
            return false;
        }
        $this->view->setVar("article", $article);
        return true;
    }

}

