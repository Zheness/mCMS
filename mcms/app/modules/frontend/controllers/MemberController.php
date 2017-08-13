<?php

namespace Mcms\Modules\Frontend\Controllers;


use Mcms\Library\Tools;
use Mcms\Models\Member;
use Mcms\Modules\Frontend\Forms\EditMemberInfoForm;
use Mcms\Modules\Frontend\Forms\PasswordLostForm;
use Mcms\Modules\Frontend\Forms\ResetPasswordForm;
use Phalcon\Filter;
use Phalcon\Text;

class MemberController extends ControllerBase
{

    public function passwordLostAction()
    {
        if ($this->session->has("member")) {
            $this->response->redirect("");
            $this->response->send();
            return false;
        }
        $form = new PasswordLostForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $email = $this->request->getPost("email");
                /** @var Member $member */
                $member = Member::findFirstByEmail($email);
                if ($member) {
                    if ($member->token == null) {
                        $member->token = Text::random(Text::RANDOM_ALNUM, rand(16, 24));
                        $member->dateUpdated = Tools::now();
                        $member->save();
                    }

                    $tools = new Tools();

                    // Send mail to member
                    $to = $member->email;
                    $subject = $this->config->site->name . ' - Mot de passe oublié';
                    $html = $this->view->getPartial("member/mail/passwordLost", [
                        "firstname" => $member->firstname,
                        "link" => $this->config->site->url . $this->url->get('member/resetPassword/' . $member->token)
                    ]);
                    $tools->sendMail($to, $subject, $html);
                    $form->clear();
                    $this->flashSession->success("La demande de mot de passe a bien été envoyée.");
                } else {
                    $this->flashSession->error("Aucun compte trouvé avec cette addresse email.");
                }
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        }
        $this->view->setVar('form', $form);
        $this->view->setVar('metaTitle', 'Mot de passe perdu');
        return true;
    }

    public function resetPasswordAction($token = null)
    {
        if ($this->session->has("member")) {
            $this->response->redirect("");
            $this->response->send();
            return false;
        }
        if ($token === null) {
            // 404
            exit("404");
        }
        /** @var Member $member */
        $member = Member::findFirstByToken($token);
        if (!$member) {
            // 404
            exit("404");
        }
        $form = new ResetPasswordForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $password = $this->request->getPost("password");
                $member->token = null;
                $member->password = $this->security->hash($password);
                $member->dateUpdated = Tools::now();
                $member->save();

                $this->flashSession->success("Votre mot de passe a bien été modifié.");

                $this->response->redirect("index/login");
                $this->response->send();
                return false;
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        }
        $this->view->setVar('member', $member);
        $this->view->setVar('form', $form);
        $this->view->setVar('metaTitle', 'Réinitialisation du mot de passe');
        return true;
    }

    public function editAction()
    {
        if (!$this->session->has('member')) {
            // 401
            exit("401");
        }
        /** @var Member $member */
        $member = $this->session->get('member');
        $form = new EditMemberInfoForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $firstname = $this->request->getPost('firstname', Filter::FILTER_SPECIAL_CHARS);
                $lastname = $this->request->getPost('lastname', Filter::FILTER_SPECIAL_CHARS);
                $email = $this->request->getPost('email', Filter::FILTER_EMAIL);
                $username = $this->request->getPost('username', Filter::FILTER_SPECIAL_CHARS);

                $member->firstname = $firstname;
                $member->lastname = $lastname;
                $member->username = $username;
                $member->email = $email;
                $member->dateUpdated = Tools::now();
                $member->updatedBy = $member->id;
                $member->save();
                $this->session->set('member', $member);
                $this->flashSession->success("Vos informations ont bien été modifiées.");
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        }
        $form->get("firstname")->setDefault($member->firstname);
        $form->get("lastname")->setDefault($member->lastname);
        $form->get("email")->setDefault($member->email);
        $form->get("username")->setDefault($member->username);
        $this->view->setVar('member', $member);
        $this->view->setVar('form', $form);
        $this->view->setVar('metaTitle', 'Modification du profil');
    }

}

