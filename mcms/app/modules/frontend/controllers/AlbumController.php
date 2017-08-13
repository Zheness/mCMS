<?php

namespace Mcms\Modules\Frontend\Controllers;

use Mcms\Library\Tools;
use Mcms\Models\Album;
use Mcms\Models\Comment;
use Mcms\Modules\Frontend\Forms\AddCommentForm;
use Phalcon\Filter;

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

    public function readAction($slug = null)
    {
        if ($slug === null) {
            // 404
            exit("404");
        }
        $album = Album::findFirstBySlug($slug);
        if (!$album) {
            // 404
            exit("404");
        }
        if ($album->isPrivate && !$this->session->has("member")) {
            exit("401");
        }

        $memberConnected = $this->session->has("member");

        $formComment = new AddCommentForm(null, ['connected' => $memberConnected]);

        if ($this->request->isPost()) {
            if ($formComment->isValid($this->request->getPost())) {
                $content = $this->request->getPost("content", [Filter::FILTER_SPECIAL_CHARS]);

                $comment = new Comment();
                $comment->ipAddress = $this->request->getClientAddress();
                $comment->albumId = $album->id;
                $comment->content = $content;
                $comment->dateCreated = Tools::now();

                if ($memberConnected) {
                    $member = $this->session->get("member");
                    $comment->createdBy = $member->id;
                    $comment->username = $member->getFullname();
                } else {
                    $username = $this->request->getPost("username", [Filter::FILTER_SPECIAL_CHARS]);
                    $comment->username = $username;
                }

                $comment->save();
                $this->flashSession->success("Le commentaire a bien été enregistré.");
                $formComment->clear();
            } else {
                $this->generateFlashSessionErrorForm($formComment);
            }
        }

        $this->view->setVar('album', $album);
        $this->view->setVar('metaTitle', $album->title);
        $this->view->setVar('formComment', $formComment);
    }

}

