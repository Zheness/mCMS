<?php

namespace Mcms\Modules\Admin\Forms;

use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Text;

class OptionGoogleReCaptchaForm extends FormBase
{
    public function initialize()
    {
        $this->add($this->sitekey());
        $this->add($this->secret());
        $this->add($this->registrationEnabled());
        $this->add($this->commentsEnabled());
    }

    private function sitekey()
    {
        $element = new Text("sitekey");
        $element->setLabel("Sitekey");
        return $element;
    }

    private function secret()
    {
        $element = new Text("secret");
        $element->setLabel("Secret");
        return $element;
    }

    private function registrationEnabled()
    {
        $element = new Check("registrationEnabled", ['value' => 'on']);
        $element->setLabel("Actif pour l'inscription");
        return $element;
    }

    private function commentsEnabled()
    {
        $element = new Check("commentsEnabled", ['value' => 'on']);
        $element->setLabel("Actif pour les commentaires");
        return $element;
    }
}
