<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Models\Image;
use Mcms\Modules\Admin\Forms\AddImageForm;
use Mcms\Library\Tools;
use Phalcon\Text;
use Phalcon\Utils\Slug;

/**
 * Class ImageController
 * @Private
 */
class ImageController extends ControllerBase
{

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
}

