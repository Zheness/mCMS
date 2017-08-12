<?php

namespace Mcms\Modules\Frontend\Controllers;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        $this->view->setVar('activeMenu', 'homepage');
    }

}

