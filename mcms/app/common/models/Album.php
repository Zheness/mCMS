<?php

namespace Mcms\Models;

class Album extends ModelBase
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
     * @Column(type="string", nullable=true)
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
        $this->setSource("album");
        $this->hasMany('id', 'Mcms\Models\AlbumImage', 'albumId', ['alias' => 'AlbumImages']);
        $this->hasMany('id', 'Mcms\Models\Comment', 'albumId', ['alias' => 'Comments']);
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
        return 'album';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Album[]|Album|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Album|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
