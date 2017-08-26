<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Library\Tools;
use Mcms\Models\Image;
use Mcms\Models\Member;
use Mcms\Models\Option;
use Mcms\Modules\Admin\Forms\AddMemberForm;
use Mcms\Modules\Admin\Forms\DeleteMemberForm;
use Mcms\Modules\Admin\Forms\EditMemberInfoForm;
use Mcms\Modules\Admin\Forms\EditMemberProfilePictureForm;
use Mcms\Modules\Admin\Forms\InviteMemberToBecomeAdminForm;
use Phalcon\Filter;
use Phalcon\Text;
use Phalcon\Utils\Slug;
use Phalcon\Validation;

/**
 * Class PageController
 * @Private
 * @Admin
 */
class MemberController extends ControllerBase
{
    /**
     * List of the members
     */
    public function indexAction()
    {
        $this->addAssetsDataTable();
        $this->assets->addJs("adminFiles/js/member.js");
    }

    /**
     * Edit a member
     * @param int $id
     * @return bool
     */
    public function editAction($id = 0)
    {
        $member = Member::findFirst($id);
        if (!$member) {
            $this->flashSession->error("Le membre séléctionné n'existe pas.");
            $this->dispatcher->forward(
                [
                    "controller" => "member",
                    "action" => "index",
                ]
            );
            return false;
        }
        if (Option::findFirstBySlug('root')->content == $member->id && $this->session->get('member')->id != $member->id) {
            $this->flashSession->error("Impossible de modifier le membre séléctionné.");
            $this->dispatcher->forward(
                [
                    "controller" => "member",
                    "action" => "index",
                ]
            );
            return false;
        }

        $form = new EditMemberInfoForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $firstname = $this->request->getPost('firstname', Filter::FILTER_SPECIAL_CHARS);
                $lastname = $this->request->getPost('lastname', Filter::FILTER_SPECIAL_CHARS);
                $email = $this->request->getPost('email', Filter::FILTER_EMAIL);
                $username = $this->request->getPost('username', Filter::FILTER_SPECIAL_CHARS);
                $password = $this->request->getPost("password");
                $role = $this->request->getPost('role');
                $status = $this->request->getPost('status');

                $member->firstname = $firstname;
                $member->lastname = $lastname;
                $member->username = $username;
                $member->email = $email;
                if (!empty($password)) {
                    $member->password = $this->security->hash($password);
                }
                $member->dateUpdated = Tools::now();
                $member->updatedBy = $this->session->get('member')->id;
                if ($member->id == $this->session->get('member')->id && $role != $member->role) {
                    $this->flashSession->warning('Impossible de changer votre propre rôle.');
                } else {
                    $member->role = $role;
                }
                if ($member->id == $this->session->get('member')->id && $status != $member->status) {
                    $this->flashSession->warning('Impossible de changer votre propre statut.');
                } else {
                    $member->status = $status;
                }
                $member->save();
                if ($member->id == $this->session->get('member')->id) {
                    $this->session->set('member', $member);
                }
                $this->flashSession->success("Les informations du membre ont bien été modifiées.");
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        } else {
            $form->get("firstname")->setDefault($member->firstname);
            $form->get("lastname")->setDefault($member->lastname);
            $form->get("email")->setDefault($member->email);
            $form->get("username")->setDefault($member->username);
            $form->get("role")->setDefault($member->role);
            $form->get("status")->setDefault($member->status);
        }
        $this->view->setVar('member', $member);
        $this->view->setVar('form', $form);
        return true;
    }


    /**
     * Add a member
     * @return bool
     */
    public function addAction()
    {
        $form = new AddMemberForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $firstname = $this->request->getPost('firstname', Filter::FILTER_SPECIAL_CHARS);
                $lastname = $this->request->getPost('lastname', Filter::FILTER_SPECIAL_CHARS);
                $email = $this->request->getPost('email', Filter::FILTER_EMAIL);
                $username = $this->request->getPost('username', Filter::FILTER_SPECIAL_CHARS);
                $password = $this->request->getPost("password");
                $role = $this->request->getPost('role');
                $status = $this->request->getPost('status');

                $member = new Member();
                $member->firstname = $firstname;
                $member->lastname = $lastname;
                if (!empty($username)) {
                    $member->username = $username;
                }
                if (!empty($password)) {
                    $member->password = $this->security->hash($password);
                }
                $member->email = $email;
                $member->dateCreated = Tools::now();
                $member->createdBy = $this->session->get('member')->id;
                $member->role = $role;
                $member->status = $status;
                $member->save();

                $this->flashSession->success("Le membre a bien été ajouté.");
                $form->clear();
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        } else {
            $form->get("role")->setDefault('member');
            $form->get("status")->setDefault(Option::findFirstBySlug('member_default_status')->content);
        }
        $this->view->setVar('form', $form);
        return true;
    }


    /**
     * Edit the profile picture of a member
     * @param int $id
     * @return bool
     */
    public function profilePictureAction($id = 0)
    {
        $member = Member::findFirst($id);
        if (!$member) {
            $this->flashSession->error("Le membre séléctionné n'existe pas.");
            $this->dispatcher->forward(
                [
                    "controller" => "member",
                    "action" => "index",
                ]
            );
            return false;
        }
        if (Option::findFirstBySlug('root')->content == $member->id && $this->session->get('member')->id != $member->id) {
            $this->flashSession->error("Impossible de modifier le membre séléctionné.");
            $this->dispatcher->forward(
                [
                    "controller" => "member",
                    "action" => "index",
                ]
            );
            return false;
        }
        $form = new EditMemberProfilePictureForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                if ($this->request->hasPost("remove")) {
                    $member->profilePicture = null;
                    $member->save();
                    $this->flashSession->success("L'image a bien été enlevée.");
                } else {
                    if ($this->request->hasFiles(true)) {
                        $file = $this->request->getUploadedFiles()[0];
                        $name = str_replace('.' . $file->getExtension(), '', $file->getName());
                        $slug = Slug::generate($name);
                        $filename = $slug . '-' . Text::random(Text::RANDOM_ALNUM, 6) . '.' . $file->getExtension();

                        $imageValidatorOption = $this->generateImageValidatorOptions();
                        $fileValidator = new Validation\Validator\File($imageValidatorOption);
                        $validator = new Validation();
                        $validator->add('file', $fileValidator);
                        $messages = $validator->validate($_FILES);

                        if (!count($messages)) {
                            $hasError = false;
                            if (!file_exists("img/upload")) {
                                if (!mkdir('img/upload')) {
                                    $this->flashSession->error("Impossible de créer le dossier de destination.");
                                    $hasError = true;
                                }
                            }

                            if (!$hasError) {
                                if ($file->moveTo('img/upload/' . $filename)) {
                                    $image = new Image();
                                    $image->filename = $filename;
                                    $image->dateCreated = Tools::now();
                                    $image->createdBy = $this->session->get('member')->id;
                                    $image->save();

                                    $member->profilePicture = $image->id;
                                    $member->dateUpdated = Tools::now();
                                    $member->updatedBy = $this->session->get('member')->id;
                                    $member->save();

                                    $this->flashSession->success("L'image a bien été enregistrée.");
                                    $form->clear();
                                } else {
                                    $this->flashSession->error("Impossible de déplacer le fichier le dossier de destination.");
                                }
                            }
                        } else {
                            foreach ($messages as $message) {
                                $this->flashSession->error($message->getMessage());
                            }
                        }
                    } else {
                        $this->flashSession->error("Le fichier est manquant.");
                    }
                }
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        }
        $this->view->setVar('member', $member);
        $this->view->setVar('form', $form);
        return true;
    }

    /**
     * Invite a member to become adminsitrator
     * @param int $id
     * @return bool
     */
    public function inviteAction($id = 0)
    {
        $member = Member::findFirst($id);
        if (!$member) {
            $this->flashSession->error("Le membre séléctionné n'existe pas.");
            $this->dispatcher->forward(
                [
                    "controller" => "member",
                    "action" => "index",
                ]
            );
            return false;
        }
        if ($member->id == $this->session->get('member')->id) {
            $this->flashSession->error("Vous ne pouvez pas vous inviter vous-même.");
            $this->dispatcher->forward(
                [
                    "controller" => "member",
                    "action" => "edit",
                    "param" => $member->id,
                ]
            );
            return false;
        }
        if (Option::findFirstBySlug('root')->content == $member->id && $this->session->get('member')->id != $member->id) {
            $this->flashSession->error("Impossible de modifier le membre séléctionné.");
            $this->dispatcher->forward(
                [
                    "controller" => "member",
                    "action" => "index",
                ]
            );
            return false;
        }
        $form = new InviteMemberToBecomeAdminForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                if ($member->token == null) {
                    $member->token = Text::random(Text::RANDOM_ALNUM, rand(16, 24));
                }
                $member->role = 'admin';
                $member->status = Member::STATUS_ACTIVE;
                $member->dateUpdated = Tools::now();
                $member->updatedBy = $this->session->get('member')->id;
                $member->save();

                $tools = new Tools();

                // Send mail to member
                $to = $member->email;
                $subject = "Invitation à devenir administrateur";
                $html = $this->view->getPartial("member/mail/invite", [
                    "firstname" => $member->firstname,
                    "linkResetPassword" => $this->config->site->url . '/member/resetPassword/' . $member->token,
                    "linkAdmin" => $this->config->site->url . '/admin'
                ]);
                $tools->sendMail($to, $subject, $html);

                $this->flashSession->success("L'invitation a bien été envoyée.");
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        }
        $this->view->setVar("form", $form);
        $this->view->setVar('member', $member);
        return true;
    }

    /**
     * Delete a member
     * @param int $id
     * @return bool
     */
    public function deleteAction($id = 0)
    {
        $member = Member::findFirst($id);
        if (!$member) {
            $this->flashSession->error("Le membre séléctionné n'existe pas.");
            $this->dispatcher->forward(
                [
                    "controller" => "member",
                    "action" => "index",
                ]
            );
            return false;
        }

        $rootId = Option::findFirstBySlug("root")->content;
        if ($member->id == $rootId) {
            $this->flashSession->error("Vous ne pouvez supprimer ce membre.");
            $this->dispatcher->forward(
                [
                    "controller" => "member",
                    "action" => "edit",
                    "param" => $member->id,
                ]
            );
            return false;
        }

        $form = new DeleteMemberForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {

                foreach ($member->PagesCreated as $page) {
                    $page->createdBy = $rootId;
                    $page->save();
                }
                foreach ($member->AlbumsCreated as $album) {
                    $album->createdBy = $rootId;
                    $album->save();
                }
                foreach ($member->AlbumImagesCreated as $albumImage) {
                    $albumImage->createdBy = $rootId;
                    $albumImage->save();
                }
                foreach ($member->ArticlesCreated as $article) {
                    $article->createdBy = $rootId;
                    $article->save();
                }
                foreach ($member->ImagesCreated as $image) {
                    $image->createdBy = $rootId;
                    $image->save();
                }
                foreach ($member->MembersCreated as $memberCreated) {
                    $memberCreated->createdBy = $rootId;
                    $memberCreated->save();
                }
                foreach ($member->MessagesCreated as $message) {
                    $message->createdBy = $rootId;
                    $message->save();
                }
                $member->delete();
                if ($this->session->get('member')->id == $member->id) {
                    $this->flashSession->success("Votre compte a bien été supprimé.");
                    $this->response->redirect("index/logout");
                    $this->response->send();
                    return false;
                }

                $this->flashSession->success("Le membre a bien été supprimé.");
                $this->dispatcher->forward(
                    [
                        "controller" => "member",
                        "action" => "index",
                    ]
                );
                return false;
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        }
        $this->view->setVar("form", $form);
        $this->view->setVar("member", $member);
        $this->view->setVar("root", Member::findFirst($rootId));
        return true;
    }



    public function commentsAction($id = 0)
    {
        $member = Member::findFirst($id);
        if (!$member) {
            $this->flashSession->error("Le membre séléctionné n'existe pas.");
            $this->dispatcher->forward(
                [
                    "controller" => "member",
                    "action" => "index",
                ]
            );
            return false;
        }
        $this->view->setVar("member", $member);
        return true;
    }

}

