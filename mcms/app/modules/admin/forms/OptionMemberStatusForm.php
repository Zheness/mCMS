<?php

namespace Mcms\Modules\Admin\Forms;

use Mcms\Models\Member;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\InclusionIn;

class OptionMemberStatusForm extends FormBase
{
    public function initialize()
    {
        $this->add($this->status());
    }

    private function status()
    {
        $element = new Select("status", Member::getStatusFr());
        $element->setLabel("Statut");
        $element->addValidator(new InclusionIn([
            "domain" => Member::getStatuses(),
            "message" => "Le statut choisi n'est pas autorisé."
        ]));
        return $element;
    }
}
