<?php

namespace Mcms\Models;

class Article extends ModelBase
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", length=90, nullable=false)
     */
    public $title;

    /**
     *
     * @var string
     * @Column(type="string", length=90, nullable=false)
     */
    public $slug;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $content;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $commentsOpen;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $dateCreated;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $dateUpdated;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $createdBy;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $updatedBy;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $datePublication;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $isPrivate;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("mcms");
        $this->setSource("article");
        $this->hasMany('id', 'Mcms\Models\Comment', 'articleId', ['alias' => 'Comments']);
        $this->belongsTo('createdBy', 'Mcms\Models\\Member', 'id', ['alias' => 'createdByMember']);
        $this->belongsTo('updatedBy', 'Mcms\Models\\Member', 'id', ['alias' => 'updatedByMember']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Article[]|Article|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Article|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'article';
    }

    public function datePublicationToFr()
    {
        return date("d/m/Y", strtotime($this->datePublication));
    }

    public function getUrl()
    {
        return $this->getDI()->get('config')->site->url . '/article/read/' . date("Y/m/d", strtotime($this->datePublication)) . '/' . $this->slug;
    }

}
