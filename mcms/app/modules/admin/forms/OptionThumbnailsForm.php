<?php

namespace Mcms\Modules\Admin\Forms;

use Phalcon\Forms\Element\Numeric;
use Phalcon\Validation\Validator\Digit;

class OptionThumbnailsForm extends FormBase
{
    public function initialize()
    {
        $this->add($this->width());
        $this->add($this->height());
    }

    private function width()
    {
        $element = new Numeric("width");
        $element->setLabel("Largeur");
        $element->addValidator(new Digit([
            "message" => "La valeur saisie n'est pas un chiffre."
        ]));
        return $element;
    }

    private function height()
    {
        $element = new Numeric("height");
        $element->setLabel("Hauteur");
        $element->addValidator(new Digit([
            "message" => "La valeur saisie n'est pas un chiffre."
        ]));
        return $element;
    }
}
