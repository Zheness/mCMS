<?php

namespace Mcms\Modules\Admin\Forms;

use Phalcon\Forms\Element\File;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\StringLength;

class AddImageForm extends FormBase
{
    public function initialize()
    {
        $this->add($this->file());
        $this->add($this->title());
        $this->add($this->description());
    }

    private function file()
    {
        $element = new File("file");
        $element->setLabel("Fichier");

        return $element;
    }

    private function title()
    {
        $element = new Text("title");
        $element->setLabel("Titre");
        $element->addValidator(new StringLength([
            "max" => 80,
            "maxMessage" => "Le titre de l'image ne peut dépasser les 80 caractères."
        ]));
        return $element;
    }

    private function description()
    {
        $element = new TextArea("description");
        $element->setLabel("Description");
        return $element;
    }
}
