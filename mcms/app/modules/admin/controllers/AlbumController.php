<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Models\Album;
use Mcms\Modules\Admin\Forms\AddAlbumForm;
use Mcms\Library\Tools;
use Phalcon\Utils\Slug;

/**
 * Class PageController
 * @Private
 * @Admin
 */
class AlbumController extends ControllerBase
{
    /**
     * List of the albums
     */
    public function indexAction()
    {
        $this->addAssetsDataTable();
        $this->assets->addJs("adminFiles/js/album.js");
    }

    /**
     * Add an album
     */
    public function addAction()
    {
        $this->assets->addCss("adminFiles/css/checkboxes-switch.css");
        $this->addAssetsTinyMce();
        $form = new AddAlbumForm();
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

                $album = Album::findFirstBySlug($slug);
                if (!$album) {
                    $album = new Album();
                    $album->title = $title;
                    $album->slug = $slug;
                    $album->content = $content;
                    $album->commentsOpen = $commentsOpen;
                    $album->isPrivate = $isPrivate;
                    $album->dateCreated = Tools::now();
                    $album->createdBy = $this->session->get('member')->id;
                    $album->save();
                    $this->flashSession->success("L'album a bien été enregistré.");
                    $form->clear();
                } else {
                    $this->flashSession->error("Un album existe déjà avec cette url.");
                }
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        }
        $this->view->setVar("form", $form);
    }
}

