<?php

namespace Mcms\Modules\Admin\Forms;

use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\InclusionIn;

class InviteMemberToBecomeAdminForm extends FormBase
{
    public function initialize()
    {
        $this->add($this->action());
    }

    private function action()
    {
        $element = new Hidden("action");
        $element->setLabel("Action");
        $element->setDefault("member-invite");
        $element->addValidator(new InclusionIn([
            "domain" => ["member-invite"],
            "message" => "L'action choisie n'est pas autorisée."
        ]));
        return $element;
    }
}
