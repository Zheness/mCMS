<?php

namespace Mcms\Modules\Frontend\Forms;

use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class EditMemberInfoForm extends FormBase
{
    public function initialize()
    {
        $this->add($this->firstname());
        $this->add($this->lastname());
        $this->add($this->email());
        $this->add($this->username());
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
            "maxMessage" => "Votre prénom ne peut dépasser les 60 caractères."
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
            "maxMessage" => "Votre nom ne peut dépasser les 60 caractères."
        ]));
        return $element;
    }

    private function username()
    {
        $element = new Text("username");
        $element->setLabel("Pseudonyme");
        $element->addValidator(new StringLength([
            "max" => 60,
            "maxMessage" => "Votre pseudonyme ne peut dépasser les 60 caractères."
        ]));
        return $element;
    }
}
