<?php

namespace Mcms\Modules\Frontend\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class AddNewMessageForm extends FormBase
{
    public function initialize()
    {
        $this->add($this->firstname());
        $this->add($this->lastname());
        $this->add($this->email());
        $this->add($this->subject());
        $this->add($this->content());
    }

    private function email()
    {
        $element = new Text("email");
        $element->setLabel("Identifiant");
        $element->addValidator(new Email([
            "message" => self::FR_VALIDATOR_EMAIL
        ]));
        return $element;
    }

    private function firstname()
    {
        $element = new Text("firstname");
        $element->setLabel("Prénom");
        $element->addValidator(new PresenceOf([
            "message" => self::FR_VALIDATOR_PRESENCE_OF
        ]));
        $element->addValidator(new StringLength([
            "max" => 60,
            "maxMessage" => "Le prénom ne peut dépasser les 60 caractères."
        ]));
        return $element;
    }

    private function lastname()
    {
        $element = new Text("lastname");
        $element->setLabel("Nom");
        $element->addValidator(new PresenceOf([
            "message" => self::FR_VALIDATOR_PRESENCE_OF
        ]));
        $element->addValidator(new StringLength([
            "max" => 60,
            "maxMessage" => "Le nom ne peut dépasser les 60 caractères."
        ]));
        return $element;
    }

    private function subject()
    {
        $element = new Text("subject");
        $element->setLabel("Sujet");
        $element->addValidator(new PresenceOf([
            "message" => self::FR_VALIDATOR_PRESENCE_OF
        ]));
        $element->addValidator(new StringLength([
            "max" => 60,
            "maxMessage" => "Le pseudonyme ne peut dépasser les 60 caractères."
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
