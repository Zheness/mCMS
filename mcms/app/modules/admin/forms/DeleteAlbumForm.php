<?php

namespace Mcms\Modules\Admin\Forms;

use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\InclusionIn;

class DeleteAlbumForm extends FormBase
{
    public function initialize()
    {
        $this->add($this->action());
    }

    private function action()
    {
        $element = new Hidden("action");
        $element->setLabel("Action");
        $element->setDefault("album-delete");
        $element->addValidator(new InclusionIn([
            "domain" => ["album-delete"],
            "message" => "L'action choisie n'est pas autorisée."
        ]));
        return $element;
    }
}
