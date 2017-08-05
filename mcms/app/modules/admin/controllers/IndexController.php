<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Models\Member;
use Mcms\Modules\Admin\Forms\LoginForm;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        if (!$this->session->has("member")) {
            $this->dispatcher->forward(["controller" => "index", "action" => "login"]);
        }
    }

    public function loginAction()
    {
        if ($this->session->has("user")) {
            $this->dispatcher->forward(["controller" => "index", "action" => "index"]);
        }
        $form = new LoginForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $email = $this->request->getPost("email");
                $password = $this->request->getPost("password");
                $member = Member::findFirstByEmail($email);
                if ($member) {
                    if ($this->security->checkHash($password, $member->password)) {
                        if ($member->role == 'admin') {
                            $this->session->set("member", $member);
                            $this->flashSession->success("Vous êtes maintenant connecté à l'administration.");
                            $this->view->disable();
                            $this->response->redirect("");
                            $this->response->send();
                        } else {
                            $this->flashSession->error("Votre compte ne vous permet pas d'accéder à l'administration.");
                        }
                    } else {
                        $this->flashSession->error("Aucun compte trouvé avec ces identifiants.");
                    }
                } else {
                    $this->flashSession->error("Aucun compte trouvé avec ces identifiants.");
                }
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        }
        $this->view->setVar("form", $form);
    }

    public function logoutAction()
    {
        if ($this->session->has("member")) {
            $this->session->remove("member");
            $this->flashSession->notice("Vous êtes maintenant déconnecté.");
        }
        $this->dispatcher->forward(["controller" => "index", "action" => "login"]);
    }

}

