<?php

namespace Mcms\Modules\Frontend\Controllers;


use Mcms\Library\Tools;
use Mcms\Models\Member;
use Mcms\Models\Message;
use Mcms\Models\Option;
use Mcms\Models\SpecialPage;
use Mcms\Modules\Frontend\Forms\AddNewMessageForm;
use Mcms\Modules\Frontend\Forms\ReplyToMessageForm;
use Phalcon\Filter;
use Phalcon\Text;

class MessageController extends ControllerBase
{

    public function newAction()
    {
        $form = new AddNewMessageForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $firstname = $this->request->getPost('firstname', Filter::FILTER_SPECIAL_CHARS);
                $lastname = $this->request->getPost('lastname', Filter::FILTER_SPECIAL_CHARS);
                $email = $this->request->getPost('email', Filter::FILTER_EMAIL);
                $subject = $this->request->getPost('subject', Filter::FILTER_SPECIAL_CHARS);
                $content = $this->request->getPost('content', Filter::FILTER_SPECIAL_CHARS);

                $message = new Message();
                $message->firstname = $firstname;
                $message->lastname = $lastname;
                $message->email = $email;
                $message->subject = $subject;
                $message->content = $content;
                $message->dateCreated = Tools::now();
                $message->token = Text::random(Text::RANDOM_ALNUM, rand(24, 32));
                $message->ipAddress = $this->request->getClientAddress();
                $message->unread = 1;

                if ($this->session->has('member')) {
                    $member = $this->session->get('member');
                    $message->firstname = $member->firstname;
                    $message->lastname = $member->lastname;
                    $message->email = $member->email;
                    $message->createdBy = $member->id;
                }
                $message->save();

                if ($this->session->has('member')) {
                    $member = $this->session->get('member');
                    $this->addLog('member', 'Nouvelle conversation (#' . $message->id . ') créée', $member->getFullname(), $member->id, 'Sujet: ' . $message->subject);
                    $this->addLog('message', 'Conversation créée par le membre #' . $member->id, $member->getFullname(), $message->id, 'Sujet: ' . $message->subject);
                } else {
                    $this->addLog('member', 'Conversation créée', 'Anonyme', $message->id, 'Sujet: ' . $message->subject);
                }

                $tools = new Tools();

                $admin = Member::findFirst(Option::findFirstBySlug('root')->content);
                // Send mail to member
                $to = $admin->email;
                $subject = "Nouveau message reçu";
                $html = $this->view->getPartial("message/mail/new", [
                    "firstname" => $admin->firstname,
                    "thread_subject" => $message->subject,
                    "link" => $this->config->site->url . '/admin/message/thread/' . $message->token
                ]);
                $tools->sendMail($to, $subject, $html);

                $this->flashSession->success("Votre message a bien été envoyé.");
                $this->response->redirect("/message/thread/" . $message->token);
                $this->response->send();
                return false;
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        } else {
            if ($this->session->has('member')) {
                /** @var Member $member */
                $member = $this->session->get('member');
                $form->get("firstname")->setDefault($member->firstname)->setAttribute("readonly", "readonly");
                $form->get("lastname")->setDefault($member->lastname)->setAttribute("readonly", "readonly");
                $form->get("email")->setDefault($member->email)->setAttribute("readonly", "readonly");
            }
        }
        $this->view->setVar('form', $form);

        $page = SpecialPage::findFirstBySlug('contact');
        $this->view->setVar('metaTitle', $page->title);
        $this->view->setVar('content', $page->content);
        $this->view->setVar('activeMenu', 'contact');
        return true;
    }

    public function threadAction($token = null)
    {
        /** @var Message $thread */
        $thread = Message::findFirstByToken($token);
        if (!$thread) {
            $this->dispatcher->forward(
                [
                    'controller' => 'error',
                    'action' => 'error404',
                ]
            );
            $this->response->setStatusCode(404);
            return false;
        }
        $form = new ReplyToMessageForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $content = $this->request->getPost('content', Filter::FILTER_SPECIAL_CHARS);

                $message = new Message();
                $message->firstname = $thread->firstname;
                $message->lastname = $thread->lastname;
                $message->email = $thread->email;
                $message->content = $content;
                $message->dateCreated = Tools::now();
                $message->ipAddress = $this->request->getClientAddress();
                $message->unread = 0;
                $message->parentId = $thread->id;

                if ($this->session->has('member')) {
                    $message->createdBy = $this->session->get('member')->id;
                }
                $message->save();

                $thread->unread = 1;
                $thread->dateUpdated = Tools::now();
                $thread->updatedBy = null;
                if ($this->session->has('member')) {
                    $thread->updatedBy = $this->session->get('member')->id;
                }
                $thread->save();

                $this->addLog('message', 'Nouveau message (#' . $message->id . ') reçu pour la conversation', 'Anonyme', $thread->id, 'Sujet: ' . $thread->subject);

                $tools = new Tools();

                $admin = Member::findFirst(Option::findFirstBySlug('root')->content);
                // Send mail to member
                $to = $admin->email;
                $subject = "Nouveau message reçu pour la conversation {$thread->subject}";
                $html = $this->view->getPartial("message/mail/new", [
                    "firstname" => $admin->firstname,
                    "thread_subject" => $thread->subject,
                    "link" => $this->config->site->url . '/admin/message/thread/' . $thread->token
                ]);
                $tools->sendMail($to, $subject, $html);

                $this->flashSession->success("Votre message a bien été envoyé.");
                $form->clear();
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        }
        $this->view->setVar('form', $form);
        $this->view->setVar('thread', $thread);
        $this->view->setVar('metaTitle', 'Répondre à une conversation');
        return true;
    }

}

