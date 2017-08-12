<?php
namespace Mcms\Modules\Frontend\Controllers;

use Mcms\Models\Page;
use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public function afterExecuteRoute()
    {
        /*
         * Latest pages
         */
        $pages = Page::find([
            'order' => 'dateCreated DESC',
            'limit' => 5
        ]);
        $this->view->setVar('menu_latestPages', $pages);
    }
}
