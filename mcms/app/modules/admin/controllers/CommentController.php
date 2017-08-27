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
    public function indexAction()
    {
        $this->addAssetsDataTable();
        $this->assets->addJs("adminFiles/js/comment.js");
    }

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
            if ($comment->articleId != null) {
                $controller = "article";
                $action = "comments";
                $params = [$comment->articleId];
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
            if ($comment->articleId != null) {
                $controller = "article";
                $action = "comments";
                $params = [$comment->articleId];
            }
            $comment = $currentComment;
        }

        $form = new DeleteCommentForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $comment->delete();

                $this->addLog('member', 'Commentaire supprimé par le membre #' . $this->session->get('member')->id, $this->session->get('member')->getFullname(), $comment->id);
                $this->addLog('comment', 'Commentaire #' . $comment->id . ' supprimé', $this->session->get('member')->getFullname(), $this->session->get('member')->id);
                $this->flashSession->success("Le commentaire a bien été supprimé.");

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
            if ($comment->articleId != null) {
                $controller = "article";
                $action = "comments";
                $params = [$comment->articleId];
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
            if ($comment->articleId != null) {
                $controller = "article";
                $action = "comments";
                $params = [$comment->articleId];
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

                $this->addLog('member', 'Commentaire #' . $reply->id . ' ajouté en réponse au commentaire #' . $comment->id . ' par le membre #' . $this->session->get('member')->id, $this->session->get('member')->getFullname(), $comment->id);
                $this->addLog('comment', 'Commentaire ajouté en réponse au commentaire #' . $comment->id, $this->session->get('member')->getFullname(), $reply->id);
                $this->addLog('comment', 'Commentaire #' . $reply->id . ' ajouté en réponse', $this->session->get('member')->getFullname(), $comment->id);
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

