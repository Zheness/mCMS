<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Models\Image;
use Mcms\Modules\Admin\Forms\AddImageForm;
use Mcms\Library\Tools;
use Mcms\Modules\Admin\Forms\DeleteImageForm;
use Mcms\Modules\Admin\Forms\EditImageForm;
use Phalcon\Text;
use Phalcon\Utils\Slug;

/**
 * Class ImageController
 * @Private
 * @Admin
 */
class ImageController extends ControllerBase
{

    /**
     * List of the images
     */
    public function indexAction()
    {
        $this->addAssetsDataTable();
        $this->assets->addJs("adminFiles/js/image.js");
    }

    /**
     * Add an image
     */
    public function addAction()
    {
        $form = new AddImageForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                if ($this->request->hasFiles(true)) {
                    $title = $this->request->getPost("title");
                    $description = $this->request->getPost("description");
                    $file = $this->request->getUploadedFiles()[0];
                    $name = str_replace('.' . $file->getExtension(), '', $file->getName());
                    $slug = Slug::generate($name);
                    $filename = $slug . '-' . Text::random(Text::RANDOM_ALNUM, 6) . '.' . $file->getExtension();

                    $hasError = false;
                    if (!file_exists("img/upload")) {
                        if (!mkdir('img/upload')) {
                            $this->flashSession->error("Impossible de créer le dossier de destination.");
                            $hasError = true;
                        }
                    }

                    if (!$hasError) {
                        if ($file->moveTo('img/upload/' . $filename)) {
                            $image = new Image();
                            $image->title = $title;
                            $image->description = $description;
                            $image->filename = $filename;
                            $image->dateCreated = Tools::now();
                            $image->createdBy = $this->session->get('member')->id;
                            $image->save();
                            $this->flashSession->success("L'image a bien été enregistrée.");
                            $form->clear();
                        } else {
                            $this->flashSession->error("Impossible de déplacer le fichier le dossier de destination.");
                        }
                    }
                } else {
                    $this->flashSession->error("Le fichier est manquant.");
                }
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        }
        $this->view->setVar("form", $form);
    }

    /**
     * Edit an image
     * @param int $id
     * @return bool
     */
    public function editAction($id = 0)
    {
        $image = Image::findFirst($id);
        if (!$image) {
            $this->flashSession->error("L'image séléctionnée n'existe pas.");
            $this->dispatcher->forward(
                [
                    "controller" => "image",
                    "action" => "index",
                ]
            );
            return false;
        }
        $form = new EditImageForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $title = $this->request->getPost("title");
                $description = $this->request->getPost("description");

                $image->title = $title;
                $image->description = $description;
                $image->dateUpdated = Tools::now();
                $image->updatedBy = $this->session->get('member')->id;
                $image->save();
                $this->flashSession->success("Les informations sur l'image ont bien été enregistrées.");
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        } else {
            $form->get("title")->setDefault($image->title);
            $form->get("description")->setDefault($image->description);
        }
        $this->view->setVar("image", $image);
        $this->view->setVar("form", $form);
        return true;
    }

    /**
     * Delete a page
     * @param int $id
     * @return bool
     */
    public function deleteAction($id = 0)
    {
        $image = Image::findFirst($id);
        if (!$image) {
            $this->flashSession->error("L'image séléctionnée n'existe pas.");
            $this->dispatcher->forward(
                [
                    "controller" => "image",
                    "action" => "index",
                ]
            );
            return false;
        }
        $form = new DeleteImageForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $hasError = false;
                if (file_exists('img/upload/' . $image->filename)) {
                    if (!unlink('img/upload/' . $image->filename)) {
                        $this->flashSession->error("Impossible de supprimer le fichier.");
                        $hasError = true;
                    }
                }
                if (!$hasError) {
                    $this->flashSession->success("L'image a bien été supprimée.");
                    $image->delete();
                    $this->dispatcher->forward(
                        [
                            "controller" => "image",
                            "action" => "index",
                        ]
                    );
                    return false;
                }
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        }
        $this->view->setVar("form", $form);
        $this->view->setVar("image", $image);
        return true;
    }
}

