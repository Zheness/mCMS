<?php

namespace Mcms\Models;

use Mcms\Library\Tools;
use Phalcon\Mvc\Model;

class ModelBase extends Model
{
    public function initialize()
    {
        $this->keepSnapshots(true);
    }

    /**
     * @return string
     */
    public function getFullnameCreator()
    {
        if ($this->createdBy == null) {
            return "-";
        }
        return $this->createdByMember->getFullname();
    }

    /**
     * @return string
     */
    public function getFullnameLastEditor()
    {
        if ($this->updatedBy == null) {
            return "-";
        }
        return $this->updatedByMember->getFullname();
    }

    public function getAdminLinkCreator()
    {
        if ($this->createdBy == null) {
            return "-";
        }
        $href = $this->getDI()->get("url")->get("member/edit/" . $this->createdBy);
        return "<a href='{$href}'>{$this->createdByMember->getFullname()}</a>";
    }

    public function getAdminLinkLastEditor()
    {
        if ($this->updatedBy == null) {
            return "-";
        }
        $href = $this->getDI()->get("url")->get("member/edit/" . $this->updatedBy);
        return "<a href='{$href}'>{$this->updatedByMember->getFullname()}</a>";
    }

    public function dateCreatedToFr()
    {
        return Tools::mysqlDateToFr($this->dateCreated);
    }

    public function dateUpdatedToFr()
    {
        return Tools::mysqlDateToFr($this->dateUpdated);
    }

    public function save($data = null, $whiteList = null)
    {
        $saved = parent::save($data, $whiteList);
        if (!$saved) {
            $content = 'Source: ' . $this->getSource() . '<br/>';
            foreach ($this->getMessages() as $message) {
                $content .= $message->getMessage() . '<br/>';
            }
            $logDb = new Log();
            $logDb->type = 'erreur';
            $logDb->action = "[Erreur] Une erreur 500 est survenue lors de la sauvegarde en base de données";
            $logDb->username = 'Mysql';
            $logDb->sourcerId = null;
            $logDb->content = $content;
            $logDb->dateCreated = Tools::now();
            $logDb->save();
            if ($this->getDI()->get('session')->has('member')) {
                $log = new Log();
                $log->type = 'member';
                $log->action = "Une erreur 500 est survenue";
                $log->username = $this->getDI()->get('session')->get('member')->getFullname();
                $log->sourcerId = $this->getDI()->get('session')->get('member')->id;
                $log->content = 'Erreur log #' . $logDb->id;
                $log->dateCreated = Tools::now();
                $log->save();
            } else {
                $log = new Log();
                $log->type = 'member';
                $log->action = "Une erreur 500 est survenue";
                $log->username = 'Anonyme';
                $log->sourcerId = null;
                $log->content = 'Erreur log #' . $logDb->id;
                $log->dateCreated = Tools::now();
                $log->save();
            }
            if ($this->getSource() == 'email' || $this->getSource() == 'log') {
            } else {
                throw new \Exception("Saving data error");
            }
        }
    }

    public function delete()
    {
        $saved = parent::delete();
        if (!$saved) {
            $content = 'Source: ' . $this->getSource() . '<br/>';
            foreach ($this->getMessages() as $message) {
                $content .= $message->getMessage() . '<br/>';
            }
            $logDb = new Log();
            $logDb->type = 'erreur';
            $logDb->action = "[Erreur] Une erreur 500 est survenue lors de la suppression en base de données";
            $logDb->username = 'Mysql';
            $logDb->sourcerId = null;
            $logDb->content = $content;
            $logDb->dateCreated = Tools::now();
            $logDb->save();
            if ($this->getDI()->get('session')->has('member')) {
                $log = new Log();
                $log->type = 'member';
                $log->action = "Une erreur 500 est survenue";
                $log->username = $this->getDI()->get('session')->get('member')->getFullname();
                $log->sourcerId = $this->getDI()->get('session')->get('member')->id;
                $log->content = 'Erreur log #' . $logDb->id;
                $log->dateCreated = Tools::now();
                $log->save();
            } else {
                $log = new Log();
                $log->type = 'member';
                $log->action = "Une erreur 500 est survenue";
                $log->username = 'Anonyme';
                $log->sourcerId = null;
                $log->content = 'Erreur log #' . $logDb->id;
                $log->dateCreated = Tools::now();
                $log->save();
            }
            if ($this->getSource() == 'email' || $this->getSource() == 'log') {
            } else {
                throw new \Exception("Deleting data error");
            }
        }
    }
}
