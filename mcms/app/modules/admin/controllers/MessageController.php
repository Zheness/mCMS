<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Library\Tools;
use Mcms\Models\Message;
use Mcms\Modules\Admin\Forms\ReplyToMessageForm;
use Phalcon\Filter;

class MessageController extends ControllerBase
{
    public function indexAction()
    {
        $this->addAssetsDataTable();
        $this->assets->addJs("adminFiles/js/message.js");
    }

    public function threadAction($token = null)
    {
        /** @var Message $thread */
        $thread = Message::findFirstByToken($token);
        if (!$thread) {
            $this->flashSession->error("La conversation séléctionnée n'existe pas.");
            $this->dispatcher->forward(
                [
                    "controller" => "message",
                    "action" => "index",
                ]
            );
            return false;
        }
        $form = new ReplyToMessageForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $content = $this->request->getPost('content', Filter::FILTER_SPECIAL_CHARS);

                $member = $this->session->get('member');
                $message = new Message();
                $message->firstname = $member->firstname;
                $message->lastname = $member->lastname;
                $message->email = $member->email;
                $message->content = $content;
                $message->dateCreated = Tools::now();
                $message->unread = 0;
                $message->parentId = $thread->id;
                $message->createdBy = $member->id;
                $message->save();

                $thread->unread = 0;
                $thread->dateUpdated = Tools::now();
                $thread->updatedBy = $member->id;
                $thread->save();

                $this->addLog('message', 'Message #' . $message->id . ' ajouté par le membre #' . $this->session->get('member')->id, $this->session->get('member')->getFullname(), $thread->id, 'Sujet: ' . $thread->subject);
                $this->addLog('member', 'Ajoute le message #' . $message->id . ' pour la conversation #' . $thread->id, $this->session->get('member')->getFullname(), $this->session->get('member')->id, 'Sujet: ' . $thread->subject);

                $this->flashSession->success("Votre réponse à la conversation a bien été envoyée.");

                $tools = new Tools();

                // Send mail to member
                $to = $thread->email;
                $subject = $thread->subject;
                $html = $this->view->getPartial("message/mail/replied", [
                    "firstname" => $thread->firstname,
                    "thread_subject" => $thread->subject,
                    "link" => $this->config->site->url . '/message/thread/' . $thread->token
                ]);
                $tools->sendMail($to, $subject, $html);

                $form->clear();
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        } else {
            if ($thread->unread == 1) {
                $thread->unread = 0;
                $thread->dateUpdated = Tools::now();
                $thread->updatedBy = $this->session->get('member')->id;
                $thread->save();
            }
        }
        $this->view->setVar('form', $form);
        $this->view->setVar('thread', $thread);
        return true;
    }

}

