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
        if ($this->session->has("member")) {
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
                            switch ($member->status) {
                                case Member::STATUS_ACTIVE:
                                    $this->session->set("member", $member);
                                    $this->addLog('member', 'Connexion à l\'administration réussie', $member->getFullname(), $member->id);
                                    $this->flashSession->success("Vous êtes maintenant connecté à l'administration.");
                                    break;
                                case Member::STATUS_PENDING:
                                    $this->addLog('member', 'Connexion annulée, le compte est en attente de validation', $member->getFullname(), $member->id);
                                    $this->flashSession->warning("Connexion échouée, votre compte est toujours en attente de validation.");
                                    break;
                                case Member::STATUS_BLOCKED:
                                    $this->addLog('member', 'Connexion annulée, le compte est bloqué', $member->getFullname(), $member->id);
                                    $this->flashSession->error("Connexion impossible, votre compte a été bloqué.");
                                    break;
                                default:
                                    $this->addLog('member', 'Statut du compte inconnu', $member->getFullname(), $member->id);
                                    $this->flashSession->error("Impossible de récupérer le statut de votre compte.");
                            }
                            $this->view->disable();
                            $this->response->redirect("");
                            $this->response->send();
                        } else {
                            $this->addLog('member', 'Tentaive de connexion avec un compte non adminstrateur', $member->getFullname(), $member->id);
                            $this->flashSession->error("Votre compte ne vous permet pas d'accéder à l'administration.");
                        }
                    } else {
                        $this->addLog('member', 'Tentaive de connexion avec mot de passe erroné', $member->getFullname(), $member->id, 'Addresse IP: ' . $this->request->getClientAddress());
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
            $member = $this->session->get('member');
            $this->addLog('member', 'Déconnexion de l\'administration réussie', $member->getFullname(), $member->id);
            $this->session->remove("member");
            $this->flashSession->notice("Vous êtes maintenant déconnecté.");
        }
        $this->dispatcher->forward(["controller" => "index", "action" => "login"]);
    }

}

