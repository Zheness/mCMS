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
            foreach ($this->getMessages() as $message) {
                $this->getDI()->get('flashSession')->error($message->getMessage());
            }
            if ($this->getSource() == 'email' || $this->getSource() == 'log') {
            } else {
                throw new \Exception("Saving data error");
            }
        }
    }
}
