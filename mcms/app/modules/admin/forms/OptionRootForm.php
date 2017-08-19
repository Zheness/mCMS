<?php

namespace Mcms\Modules\Admin\Forms;

use Phalcon\Forms\Element\Numeric;
use Phalcon\Validation\Validator\Digit;

class OptionRootForm extends FormBase
{
    public function initialize()
    {
        $this->add($this->id());
    }

    private function id()
    {
        $element = new Numeric("id");
        $element->setLabel("Id du membre");
        $element->addValidator(new Digit([
            "message" => "La valeur saisie n'est pas un chiffre."
        ]));
        return $element;
    }
}
