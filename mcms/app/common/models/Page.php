<?php

namespace Mcms\Models;

use Mcms\Library\Tools;

class Page extends ModelBase
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $Id;

    /**
     *
     * @var string
     * @Column(type="string", length=90, nullable=false)
     */
    public $Title;

    /**
     *
     * @var string
     * @Column(type="string", length=90, nullable=false)
     */
    public $Slug;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $Content;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $Commentsopen;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $Datecreated;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $Dateupdated;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $Createdby;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $Updatedby;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $Isprivate;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("mcms");
        $this->setSource("page");
        $this->hasMany('id', 'Mcms\Models\Comment', 'pageId', ['alias' => 'Comments']);
        $this->belongsTo('createdBy', 'Mcms\Models\\Member', 'id', ['alias' => 'createdByMember']);
        $this->belongsTo('updatedBy', 'Mcms\Models\\Member', 'id', ['alias' => 'updatedByMember']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'page';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Page[]|Page|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Page|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
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

    /**
     * Returns the public url of the page
     * @return string
     */
    public function getUrl()
    {
        return "/page/" . $this->slug;
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

}
