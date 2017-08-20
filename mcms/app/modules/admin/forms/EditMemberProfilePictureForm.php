<?php

namespace Mcms\Modules\Admin\Forms;

use Phalcon\Forms\Element\File;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\StringLength;

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
