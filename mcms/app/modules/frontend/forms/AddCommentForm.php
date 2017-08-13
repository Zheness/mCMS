<?php

namespace Mcms\Modules\Frontend\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class AddCommentForm extends FormBase
{
    public function initialize($entity = null, $options = [])
    {
        if (isset($options['connected']) && !$options['connected']) {
            $this->add($this->username());
        }
        $this->add($this->content());
    }

    private function username()
    {
        $element = new Text("username");
        $element->setLabel("Nom");
        $element->addValidator(new PresenceOf([
            "message" => self::FR_VALIDATOR_PRESENCE_OF
        ]));
        $element->addValidator(new StringLength([
            "max" => 60,
            "maxMessage" => "Votre nom ne peut dépasser les 60 caractères."
        ]));
        return $element;
    }

    private function content()
    {
        $element = new TextArea("content");
        $element->setLabel("Message");
        $element->addValidator(new PresenceOf([
            "message" => self::FR_VALIDATOR_PRESENCE_OF
        ]));
        return $element;
    }
}
