<?php

namespace Mcms\Modules\Admin\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class EditSpecialPageForm extends FormBase
{
    public function initialize()
    {
        $this->add($this->title());
        $this->add($this->content());
    }

    private function title()
    {
        $element = new Text("title");
        $element->setLabel("Titre");
        $element->addValidator(new PresenceOf([
            "message" => self::FR_VALIDATOR_PRESENCE_OF
        ]));
        $element->addValidator(new StringLength([
            "max" => 80,
            "maxMessage" => "Le titre de la page ne peut dépasser les 80 caractères."
        ]));
        return $element;
    }

    private function content()
    {
        $element = new TextArea("content");
        $element->setLabel("Contenu");
        $element->addValidator(new PresenceOf([
            "message" => self::FR_VALIDATOR_PRESENCE_OF
        ]));
        return $element;
    }
}
