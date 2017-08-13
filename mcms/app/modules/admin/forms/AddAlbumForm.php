<?php

namespace Mcms\Modules\Admin\Forms;

use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\StringLength;

class AddAlbumForm extends FormBase
{
    public function initialize()
    {
        $this->add($this->title());
        $this->add($this->slug());
        $this->add($this->content());
        $this->add($this->commentsOpen());
        $this->add($this->isPrivate());
    }

    private function title()
    {
        $element = new Text("title");
        $element->setLabel("Titre");
        $element->addValidator(new PresenceOf([
            "message" => self::FR_VALIDATOR_PRESENCE_OF
        ]));
        $element->addValidator(new StringLength([
            "max" => 80,
            "maxMessage" => "Le titre de l'album ne peut dépasser les 80 caractères."
        ]));
        return $element;
    }

    private function slug()
    {
        $element = new Text("slug");
        $element->setLabel("Url");
        $element->addValidator(new StringLength([
            "max" => 80,
            "maxMessage" => "L'url de l'album ne peut dépasser les 80 caractères."
        ]));
        $element->addValidator(new Regex([
            "pattern" => '/^[a-z0-9\-]+$/i',
            "message" => "L'url de l'album ne peut contenir que des lettres et chiffres (de A à Z et de 0 à 9) et des tirets (-).",
            "allowEmpty" => true,
        ]));
        return $element;
    }

    private function content()
    {
        $element = new TextArea("content");
        $element->setLabel("Contenu");
        return $element;
    }

    private function commentsOpen()
    {
        $element = new Check("commentsOpen", ['value' => 'on']);
        $element->setLabel("Commentaires ouverts");
        return $element;
    }

    private function isPrivate()
    {
        $element = new Check("isPrivate", ['value' => 'on']);
        $element->setLabel("Album privé");
        return $element;
    }
}
