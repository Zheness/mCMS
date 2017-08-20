<?php

namespace Mcms\Modules\Frontend\Forms;

use Phalcon\Forms\Element\File;

class EditMemberProfilePictureForm extends FormBase
{
    public function initialize()
    {
        $this->add($this->file());
    }

    private function file()
    {
        $element = new File("file");
        $element->setLabel("Fichier");

        return $element;
    }
}
