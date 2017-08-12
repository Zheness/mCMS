<?php

namespace Mcms\Modules\Frontend\Controllers;

use Mcms\Models\Member;
use Mcms\Modules\Frontend\Forms\LoginForm;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        $this->view->setVar('activeMenu', 'homepage');
    }

    public function loginAction()
    {
        if ($this->session->has("member")) {
            $this->response->redirect("");
            $this->response->send();
            return false;
        }
        $form = new LoginForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $email = $this->request->getPost("email");
                $password = $this->request->getPost("password");
                $member = Member::findFirstByEmail($email);
                if ($member) {
                    if ($this->security->checkHash($password, $member->password)) {
                        $this->session->set("member", $member);
                        $this->flashSession->success("Vous êtes maintenant connecté à l'espace membre.");
                        $this->view->disable();
                        $this->response->redirect("");
                        $this->response->send();
                        return false;
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
        $this->view->setVar('metaTitle', 'Connexion à l\'espace membre');
        $this->view->setVar('activeMenu', 'login');
        return true;
    }

    public function logoutAction()
    {
        if ($this->session->has("member")) {
            $this->session->remove("member");
            $this->flashSession->notice("Vous êtes maintenant déconnecté.");
        }
        $this->dispatcher->forward(["controller" => "index", "action" => "index"]);
    }

}

