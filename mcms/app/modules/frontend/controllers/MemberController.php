<?php

namespace Mcms\Modules\Frontend\Controllers;


use Mcms\Library\Tools;
use Mcms\Models\Image;
use Mcms\Models\Member;
use Mcms\Models\Option;
use Mcms\Modules\Frontend\Forms\DeleteMemberForm;
use Mcms\Modules\Frontend\Forms\EditMemberInfoForm;
use Mcms\Modules\Frontend\Forms\EditMemberProfilePictureForm;
use Mcms\Modules\Frontend\Forms\PasswordLostForm;
use Mcms\Modules\Frontend\Forms\ResetPasswordForm;
use Phalcon\Filter;
use Phalcon\Text;
use Phalcon\Utils\Slug;
use Phalcon\Validation;

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
        } else {
            $form->get("firstname")->setDefault($member->firstname);
            $form->get("lastname")->setDefault($member->lastname);
            $form->get("email")->setDefault($member->email);
            $form->get("username")->setDefault($member->username);
        }
        $this->view->setVar('member', $member);
        $this->view->setVar('form', $form);
        $this->view->setVar('metaTitle', 'Modification du profil');
    }

    public function passwordAction()
    {
        if (!$this->session->has('member')) {
            // 401
            exit("401");
        }
        /** @var Member $member */
        $member = $this->session->get('member');
        $form = new ResetPasswordForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $password = $this->request->getPost("password");
                $member->token = null;
                $member->password = $this->security->hash($password);
                $member->dateUpdated = Tools::now();
                $member->updatedBy = $member->id;
                $member->save();

                $this->flashSession->success("Votre mot de passe a bien été modifié.");
                $form->clear();
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        }
        $this->view->setVar('member', $member);
        $this->view->setVar('form', $form);
        $this->view->setVar('metaTitle', 'Modification du mot de passe');
    }

    public function profilePictureAction()
    {
        if (!$this->session->has('member')) {
            // 401
            exit("401");
        }
        /** @var Member $member */
        $member = $this->session->get('member');
        $form = new EditMemberProfilePictureForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
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
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        }
        $this->view->setVar('member', $member);
        $this->view->setVar('form', $form);
        $this->view->setVar('metaTitle', 'Modification de l\'image de profil');
        return true;
    }

    /**
     * @return array
     */
    private function generateImageValidatorOptions()
    {
        $response = [];

        if ($this->config->module->image->maxSize !== false) {
            $response['maxSize'] = $this->config->module->image->maxSize;
            $response['messageSize'] = "Le poids de l'image est trop grand (max {$this->config->module->image->maxSize})";
        }
        if ($this->config->module->image->maxResolution !== false) {
            $response['maxResolution'] = $this->config->module->image->maxResolution;
            $response['messageMaxResolution'] = "L'image est trop grande (résolution maximum {$this->config->module->image->maxResolution})";
        }
        if ($this->config->module->image->allowedTypes !== false && count($this->config->module->image->allowedTypes)) {
            $response['allowedTypes'] = (array)$this->config->module->image->allowedTypes;
            $response['messageType'] = "Le format de l'image n'est pas supporté (types autorisés: :types)";
        }
        return $response;
    }

    public function unsubscribeAction()
    {
        if (!$this->session->has('member')) {
            // 401
            exit("401");
        }
        /** @var Member $member */
        $member = $this->session->get('member');
        if ($member->role == 'admin') {
            $this->response->redirect("admin/member/delete/" . $member->id);
            $this->response->send();
            return false;
        }

        $rootId = Option::findFirstBySlug("root")->content;
        if ($member->id == $rootId) {
            $this->flashSession->error("Vous ne pouvez pas supprimer votre compte.");
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

                $this->flashSession->success("Votre compte a bien été supprimé.");
                $this->response->redirect("index/logout");
                $this->response->send();
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

}

