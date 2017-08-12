<?php

namespace Mcms\Modules\Admin\Forms;

use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;

class ReplyToCommentForm extends FormBase
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
