<?php

namespace Mcms\Modules\Frontend\Forms;

use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\PresenceOf;

class ResetPasswordForm extends FormBase
{
    public function initialize()
    {
        $this->add($this->password());
        $this->add($this->passwordConfirm());
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

    private function passwordConfirm()
    {
        $element = new Password("passwordConfirm");
        $element->setLabel("Confirmation du mot de passe");
        $element->addValidator(new Confirmation([
            "message" => "Les mots de passe sont différents",
            "with" => "password",
        ]));
        return $element;
    }
}
