<?php

namespace Mcms\Modules\Frontend\Controllers;

use Mailgun\Mailgun;
use Mcms\Library\Tools;
use Mcms\Models\Member;
use Mcms\Models\Option;
use Mcms\Modules\Frontend\Forms\LoginForm;
use Mcms\Modules\Frontend\Forms\SignupForm;
use Phalcon\Filter;
use Phalcon\Mvc\View;

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
                        $member->token = null;
                        $member->save();
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

    public function signupAction()
    {
        if ($this->session->has("member")) {
            $this->response->redirect("");
            $this->response->send();
            return false;
        }
        $form = new SignupForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $email = $this->request->getPost("email");
                $password = $this->request->getPost("password");
                $firstname = $this->request->getPost("firstname", [Filter::FILTER_SPECIAL_CHARS]);
                $lastname = $this->request->getPost("lastname", [Filter::FILTER_SPECIAL_CHARS]);
                $member = Member::findFirstByEmail($email);
                if (!$member) {
                    $member = new Member();
                    $member->firstname = $firstname;
                    $member->lastname = $lastname;
                    $member->email = $email;
                    $member->password = $this->security->hash($password);
                    $member->dateCreated = Tools::now();
                    $member->save();
                    $this->session->set("member", $member);
                    $this->flashSession->success("Inscription réussie ! Vous êtes maintenant connecté à l'espace membre.");
                    $this->view->disable();
                    $this->response->redirect("");
                    $this->response->send();
                    return false;
                } else {
                    $this->flashSession->error("Cette addresse email est déjà utilisée.");
                }
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        }
        $this->view->setVar("form", $form);
        $this->view->setVar('metaTitle', 'Inscription à l\'espace membre');
        $this->view->setVar('activeMenu', 'signup');
        return true;
    }

    public function maintenanceAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('message', Option::findFirstBySlug('maintenance_message')->content);
    }

}

