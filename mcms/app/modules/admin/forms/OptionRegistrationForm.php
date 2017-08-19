<?php

namespace Mcms\Modules\Admin\Forms;

use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;

class OptionRegistrationForm extends FormBase
{
    public function initialize()
    {
        $this->add($this->enabled());
    }

    private function enabled()
    {
        $element = new Check("enabled", ['value' => 'on']);
        $element->setLabel("Inscription autorisée");
        return $element;
    }
}
