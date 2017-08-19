<?php

namespace Mcms\Modules\Admin\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;

class OptionCookieConsentForm extends FormBase
{
    public function initialize()
    {
        $this->add($this->text());
        $this->add($this->textButton());
        $this->add($this->textLink());
        $this->add($this->backgroundColor());
        $this->add($this->backgroundColorButton());
        $this->add($this->textColor());
        $this->add($this->textColorButton());
        $this->add($this->textColorLink());
    }

    private function text()
    {
        $element = new Text("text");
        $element->setLabel("Texte");
        $element->addValidator(new PresenceOf([
            "message" => self::FR_VALIDATOR_PRESENCE_OF
        ]));
        return $element;
    }

    private function textButton()
    {
        $element = new Text("textButton");
        $element->setLabel("Texte du bouton");
        $element->addValidator(new PresenceOf([
            "message" => self::FR_VALIDATOR_PRESENCE_OF
        ]));
        return $element;
    }

    private function textLink()
    {
        $element = new Text("textLink");
        $element->setLabel("Texte du lien");
        $element->addValidator(new PresenceOf([
            "message" => self::FR_VALIDATOR_PRESENCE_OF
        ]));
        return $element;
    }

    private function backgroundColor()
    {
        $element = new Text("backgroundColor");
        $element->setLabel("Couleur de fond");
        $element->addValidator(new Regex(
            [
                "pattern" => "/^#[0-9A-F]{6}$/i",
                "message" => "Une valeur héxadécimale est attendue (ex: #000000)",
            ]
        ));
        return $element;
    }

    private function backgroundColorButton()
    {
        $element = new Text("backgroundColorButton");
        $element->setLabel("Couleur du bouton");
        $element->addValidator(new Regex(
            [
                "pattern" => "/^#[0-9A-F]{6}$/i",
                "message" => "Une valeur héxadécimale est attendue (ex: #000000)",
            ]
        ));
        return $element;
    }

    private function textColor()
    {
        $element = new Text("textColor");
        $element->setLabel("Couleur du texte");
        $element->addValidator(new Regex(
            [
                "pattern" => "/^#[0-9A-F]{6}$/i",
                "message" => "Une valeur héxadécimale est attendue (ex: #000000)",
            ]
        ));
        return $element;
    }

    private function textColorButton()
    {
        $element = new Text("textColorButton");
        $element->setLabel("Couleur du texte du bouton");
        $element->addValidator(new Regex(
            [
                "pattern" => "/^#[0-9A-F]{6}$/i",
                "message" => "Une valeur héxadécimale est attendue (ex: #000000)",
            ]
        ));
        return $element;
    }

    private function textColorLink()
    {
        $element = new Text("textColorLink");
        $element->setLabel("Couleur du lien");
        $element->addValidator(new Regex(
            [
                "pattern" => "/^#[0-9A-F]{6}$/i",
                "message" => "Une valeur héxadécimale est attendue (ex: #000000)",
            ]
        ));
        return $element;
    }
}
