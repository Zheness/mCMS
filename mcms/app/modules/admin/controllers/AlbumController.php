<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Models\Album;
use Mcms\Modules\Admin\Forms\AddAlbumForm;
use Mcms\Library\Tools;
use Mcms\Modules\Admin\Forms\DeleteAlbumForm;
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

    /**
     * Edit a album
     * @param int $id
     * @return bool
     */
    public function editAction($id = 0)
    {
        $album = Album::findFirst($id);
        if (!$album) {
            $this->flashSession->error("L'album séléctionné n'existe pas.");
            $this->dispatcher->forward(
                [
                    "controller" => "album",
                    "action" => "index",
                ]
            );
            return false;
        }

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

                $albumWithSlug = Album::findFirstBySlug($slug);
                if (!$albumWithSlug || $albumWithSlug->id == $album->id) {
                    $album->title = $title;
                    $album->slug = $slug;
                    $album->content = $content;
                    $album->commentsOpen = $commentsOpen;
                    $album->isPrivate = $isPrivate;
                    $album->dateUpdated = Tools::now();
                    $album->updatedBy = $this->session->get('member')->id;
                    $album->save();
                    $this->flashSession->success("L'album a bien été enregistré.");
                } else {
                    $this->flashSession->error("Un album existe déjà avec cette url.");
                }
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        } else {
            $form->get("title")->setDefault($album->title);
            $form->get("slug")->setDefault($album->slug);
            $form->get("content")->setDefault($album->content);
            if ($album->commentsOpen) {
                $form->get("commentsOpen")->setAttribute('checked', 'checked');
            }
            if ($album->isPrivate) {
                $form->get("isPrivate")->setAttribute('checked', 'checked');
            }
        }
        $this->view->setVar("album", $album);
        $this->view->setVar("form", $form);
        return true;
    }

    /**
     * Delete an album
     * @param int $id
     * @return bool
     */
    public function deleteAction($id = 0)
    {
        $album = Album::findFirst($id);
        if (!$album) {
            $this->flashSession->error("L'album séléctionné n'existe pas.");
            $this->dispatcher->forward(
                [
                    "controller" => "page",
                    "action" => "index",
                ]
            );
            return false;
        }
        $form = new DeleteAlbumForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $this->flashSession->success("L'album a bien été supprimé.");
                $album->delete();
                $this->dispatcher->forward(
                    [
                        "controller" => "album",
                        "action" => "index",
                    ]
                );
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        }
        $this->view->setVar("form", $form);
        $this->view->setVar("album", $album);
        return true;
    }

    /**
     * Manage images of an album
     * @param int $id
     * @return bool
     */
    public function imagesAction($id = 0)
    {
        $this->assets->addJs("adminFiles/js/album.js");
        $album = Album::findFirst($id);
        if (!$album) {
            $this->flashSession->error("L'album séléctionné n'existe pas.");
            $this->dispatcher->forward(
                [
                    "controller" => "page",
                    "action" => "index",
                ]
            );
            return false;
        }
        $this->view->setVar("album", $album);
        return true;
    }
}

