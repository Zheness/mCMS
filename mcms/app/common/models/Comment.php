<?php

namespace Mcms\Models;

class Comment extends ModelBase
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
     * @Column(type="string", nullable=false)
     */
    public $Content;

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
     * @Column(type="integer", length=11, nullable=true)
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
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $Ipaddress;

    /**
     *
     * @var string
     * @Column(type="string", length=70, nullable=true)
     */
    public $Username;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $Parentid;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $Articleid;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $Albumid;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $Pageid;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("mcms");
        $this->setSource("comment");
        $this->hasMany('id', 'Mcms\Models\Comment', 'parentId', ['alias' => 'Comments']);
        $this->belongsTo('albumId', 'Mcms\Models\\Album', 'id', ['alias' => 'Album']);
        $this->belongsTo('articleId', 'Mcms\Models\\Article', 'id', ['alias' => 'Article']);
        $this->belongsTo('parentId', 'Mcms\Models\\Comment', 'id', ['alias' => 'ParentComment']);
        $this->belongsTo('pageId', 'Mcms\Models\\Page', 'id', ['alias' => 'Page']);
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
        return 'comment';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Comment[]|Comment|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Comment|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
