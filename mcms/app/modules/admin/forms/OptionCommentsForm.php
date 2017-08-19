<?php

namespace Mcms\Modules\Admin\Forms;

use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Validation\Validator\Numericality;

class OptionCommentsForm extends FormBase
{
    public function initialize()
    {
        $this->add($this->enabled());
        $this->add($this->pagesEnabled());
        $this->add($this->albumsEnabled());
        $this->add($this->articlesEnabled());
        $this->add($this->maximumCommnentsPerDay());
    }

    private function enabled()
    {
        $element = new Check("enabled", ['value' => 'on']);
        $element->setLabel("Commentaires autorisés");
        return $element;
    }

    private function pagesEnabled()
    {
        $element = new Check("pagesEnabled", ['value' => 'on']);
        $element->setLabel("Commentaires autorisés sur les pages");
        return $element;
    }

    private function albumsEnabled()
    {
        $element = new Check("albumsEnabled", ['value' => 'on']);
        $element->setLabel("Commentaires autorisés sur les albums");
        return $element;
    }

    private function articlesEnabled()
    {
        $element = new Check("articlesEnabled", ['value' => 'on']);
        $element->setLabel("Commentaires autorisés sur les articles");
        return $element;
    }

    private function maximumCommnentsPerDay()
    {
        $element = new Numeric("maximumCommentsPerDay");
        $element->setLabel("Nombre de messages maximum par jour");
        $element->addValidator(new Numericality([
            "message" => "La valeur saisie n'est pas un chiffre."
        ]));
        return $element;
    }
}
