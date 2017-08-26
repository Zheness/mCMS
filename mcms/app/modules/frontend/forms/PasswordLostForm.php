<?php

namespace Mcms\Modules\Frontend\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\Email;

class PasswordLostForm extends FormBase
{
    public function initialize()
    {
        $this->add($this->email());
    }

    private function email()
    {
        $element = new Text("email");
        $element->setLabel("Email");
        $element->addValidator(new Email([
            "message" => self::FR_VALIDATOR_EMAIL
        ]));
        return $element;
    }
}
