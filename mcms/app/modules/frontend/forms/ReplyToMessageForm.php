<?php

namespace Mcms\Modules\Frontend\Forms;

use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;

class ReplyToMessageForm extends FormBase
{
    public function initialize()
    {
        $this->add($this->content());
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
