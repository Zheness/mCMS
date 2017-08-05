<?php

namespace Mcms\Modules\Admin\Forms;

use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;

class LoginForm extends FormBase
{
    public function initialize()
    {
        $this->add($this->email());
        $this->add($this->password());
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

    private function password()
    {
        $element = new Password("password");
        $element->setLabel("Mot de passe");
        $element->addValidator(new PresenceOf([
            "message" => self::FR_VALIDATOR_PRESENCE_OF
        ]));
        return $element;
    }
}
