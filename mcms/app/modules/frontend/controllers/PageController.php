<?php

namespace Mcms\Modules\Frontend\Controllers;

use Mcms\Models\Page;

class PageController extends ControllerBase
{

    public function indexAction()
    {
        $pages = Page::find([
            'conditions' => $this->session->has('member') ? null : 'isPrivate = 0'
        ]);
        $this->view->setVar('pages', $pages);
        $this->view->setVar('activeMenu', 'pages');
    }

    public function readAction($slug = null)
    {
        if ($slug === null) {
            // 404
            exit("404");
        }
        $page = Page::findFirstBySlug($slug);
        if (!$page) {
            // 404
            exit("404");
        }
        if ($page->isPrivate && !$this->session->has("member")) {
            exit("401");
        }
        $this->view->setVar('page', $page);
        $this->view->setVar('metaTitle', $page->title);
    }

}

