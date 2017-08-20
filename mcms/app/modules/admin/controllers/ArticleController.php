<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Models\Article;
use Mcms\Models\Page;
use Mcms\Modules\Admin\Forms\AddArticleForm;
use Mcms\Modules\Admin\Forms\AddPageForm;
use Mcms\Library\Tools;
use Mcms\Modules\Admin\Forms\DeletePageForm;
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

}

