<?php

namespace Mcms\Modules\Frontend\Controllers;


use Mcms\Library\Tools;
use Mcms\Models\Member;
use Mcms\Modules\Frontend\Forms\PasswordLostForm;
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

}

