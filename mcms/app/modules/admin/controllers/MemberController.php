<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Library\Tools;
use Mcms\Models\Member;
use Mcms\Modules\Admin\Forms\AddMemberForm;
use Mcms\Modules\Admin\Forms\EditMemberInfoForm;
use Phalcon\Filter;

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
                    "controller" => "album",
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
                $member->save();

                $this->flashSession->success("Le membre a bien été ajouté.");
                $form->clear();
            } else {
                $this->generateFlashSessionErrorForm($form);
            }
        }
        $this->view->setVar('form', $form);
        return true;
    }

}

