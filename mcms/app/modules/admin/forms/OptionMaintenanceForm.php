<?php

namespace Mcms\Modules\Admin\Forms;

use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;

class OptionMaintenanceForm extends FormBase
{
    public function initialize()
    {
        $this->add($this->content());
        $this->add($this->enabled());
    }

    private function content()
    {
        $element = new TextArea("content");
        $element->setLabel("Message");
        if ($this->request->isPost()) {
            $enabled = $this->request->getPost("enabled") == "on" ? 1 : 0;
            if ($enabled) {
                $element->addValidator(new PresenceOf([
                    "message" => self::FR_VALIDATOR_PRESENCE_OF
                ]));
            }
        }
        return $element;
    }

    private function enabled()
    {
        $element = new Check("enabled", ['value' => 'on']);
        $element->setLabel("Site en maintenance");
        return $element;
    }
}
