<?php

namespace Mcms\Modules\Admin\Controllers;

/**
 * Class PageController
 * @Private
 * @Admin
 */
class MemberController extends ControllerBase
{
    /**
     * List of the members
     */
    public function indexAction()
    {
        $this->addAssetsDataTable();
        $this->assets->addJs("adminFiles/js/member.js");
    }

}

