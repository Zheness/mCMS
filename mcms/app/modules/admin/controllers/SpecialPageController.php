<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Models\SpecialPage;

/**
 * Class SpecialPageController
 * @Private
 * @Admin
 */
class SpecialPageController extends ControllerBase
{
    public function indexAction()
    {
        $this->view->setVar('pages', SpecialPage::find());
    }
}

