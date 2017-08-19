<?php

namespace Mcms\Modules\Admin\Forms;

use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\PresenceOf;

class OptionNotificationForm extends FormBase
{
    public function initialize()
    {
        $this->add($this->content());
        $this->add($this->type());
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
        $element->setLabel("Notification activée");
        return $element;
    }

    private function type()
    {
        $element = new Select("type", ['info' => 'Information', 'success' => 'Succès', 'warning' => 'Avertissement', 'danger' => 'Danger']);
        $element->setLabel("Type");
        $element->addValidator(new InclusionIn([
            "domain" => ["info", "success", 'warning', 'danger'],
            "message" => "Le type de notification choisi n'est pas autorisé."
        ]));
        return $element;
    }
}
