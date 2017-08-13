<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Library\Tools;
use Mcms\Models\Comment;
use Mcms\Modules\Admin\Forms\DeleteCommentForm;
use Mcms\Modules\Admin\Forms\ReplyToCommentForm;
use Phalcon\Filter;

/**
 * Class PageController
 * @Private
 * @Admin
 */
class CommentController extends ControllerBase
{

    /**
     * Delete a comment
     * @param int $id
     * @return bool
     */
    public function deleteAction($id = 0)
    {
        $comment = Comment::findFirst($id);
        if (!$comment) {
            $this->flashSession->error("Le commentaire séléctionné n'existe pas.");
            $this->dispatcher->forward(
                [
                    "controller" => "comment",
                    "action" => "index",
                ]
            );
            return false;
        }

        $controller = 'comment';
        $action = 'index';
        $params = [];

        if ($comment->parentId == null) {
            // We generate the controller/action of the location of comment
            if ($comment->pageId != null) {
                $controller = "page";
                $action = "comments";
                $params = [$comment->pageId];
            }
            if ($comment->albumId != null) {
                $controller = "album";
                $action = "comments";
                $params = [$comment->albumId];
            }
        } else {
            // If it's an answer, we get the parent and search the location
            $currentComment = $comment;
            /** @var Comment $comment */
            $comment = $comment->ParentComment;

            if ($comment->pageId != null) {
                $controller = "page";
                $action = "comments";
                $params = [$comment->pageId];
            }
            if ($comment->albumId != null) {
                $controller = "album";
                $action = "comments";
                $params = [$comment->albumId];
            }
            $comment = $currentComment;
        }

        $form = new DeleteCommentForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $this->flashSession->success("Le commentaire a bien été supprimé.");

                $comment->delete();

                $this->dispatcher->forward(
                    [
                        "controller" => $controller,
                        "action" => $action,
                        "params" => $params,
                    ]
                );
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        }
        $this->view->setVar("form", $form);
        $this->view->setVar("comment", $comment);
        return true;
    }

    /**
     * replay to a comment
     * @param int $id
     * @return bool
     */
    public function replyAction($id = 0)
    {
        $comment = Comment::findFirst($id);
        if (!$comment) {
            $this->flashSession->error("Le commentaire séléctionné n'existe pas.");
            $this->dispatcher->forward(
                [
                    "controller" => "comment",
                    "action" => "index",
                ]
            );
            return false;
        }

        $controller = 'comment';
        $action = 'index';
        $params = [];

        if ($comment->parentId == null) {
            // We generate the controller/action of the location of comment
            if ($comment->pageId != null) {
                $controller = "page";
                $action = "comments";
                $params = [$comment->pageId];
            }
            if ($comment->albumId != null) {
                $controller = "album";
                $action = "comments";
                $params = [$comment->albumId];
            }
        } else {
            // If it's an answer, we get the parent and search the location
            $currentComment = $comment;
            /** @var Comment $comment */
            $comment = $comment->ParentComment;

            if ($comment->pageId != null) {
                $controller = "page";
                $action = "comments";
                $params = [$comment->pageId];
            }
            if ($comment->albumId != null) {
                $controller = "album";
                $action = "comments";
                $params = [$comment->albumId];
            }
            $comment = $currentComment;
        }

        if ($comment->parentId != null) {
            // mCMS currently does not support multi-level comments
            $this->flashSession->error("Impossible de répondre à ce commentaire.");
            $this->dispatcher->forward(
                [
                    "controller" => $controller,
                    "action" => $action,
                    "params" => $params,
                ]
            );
            return false;
        }

        $form = new ReplyToCommentForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $content = $this->request->getPost("content", [Filter::FILTER_SPECIAL_CHARS]);

                $reply = new Comment();
                $reply->parentId = $comment->id;
                $reply->content = $content;
                $reply->dateCreated = Tools::now();

                $member = $this->session->get("member");
                $reply->createdBy = $member->id;
                $reply->username = $member->getFullname();

                $reply->save();
                $this->flashSession->success("Le commentaire a bien été enregistré.");
                $this->dispatcher->forward(
                    [
                        "controller" => $controller,
                        "action" => $action,
                        "params" => $params,
                    ]
                );
                return false;
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        }
        $this->view->setVar("form", $form);
        $this->view->setVar("comment", $comment);
        return true;
    }

}

