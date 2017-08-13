<?php

namespace Mcms\Modules\Frontend\Controllers;

use Mcms\Models\Album;

class AlbumController extends ControllerBase
{

    public function indexAction()
    {
        $albums = Album::find([
            'conditions' => $this->session->has('member') ? null : 'isPrivate = 0'
        ]);
        $this->view->setVar('albums', $albums);
        $this->view->setVar('activeMenu', 'albums');
    }

}

