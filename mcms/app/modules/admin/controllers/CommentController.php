<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Models\Comment;
use Mcms\Models\Member;
use Mcms\Modules\Admin\Forms\DeleteCommentForm;
use Mcms\Modules\Admin\Forms\LoginForm;

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
        $form = new DeleteCommentForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $this->flashSession->success("Le commentaire a bien été supprimé.");

                $controller = 'comment';
                $action = 'index';
                $params = [];

                if ($comment->pageId != null) {
                    $controller = "page";
                    $action = "comments";
                    $params = [$comment->pageId];
                }

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

}

