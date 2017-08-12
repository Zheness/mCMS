<?php

namespace Mcms\Models;

use Phalcon\Validation\Validator\Email as EmailValidator;

class Member extends ModelBase
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
     * @Column(type="string", length=70, nullable=true)
     */
    public $firstname;

    /**
     *
     * @var string
     * @Column(type="string", length=70, nullable=true)
     */
    public $lastname;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    public $email;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $password;

    /**
     *
     * @var string
     * @Column(type="string", length=70, nullable=true)
     */
    public $username;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $role;

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
     * @Column(type="integer", length=11, nullable=true)
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
     * @Column(type="string", length=100, nullable=true)
     */
    public $token;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $profilePicture;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'Email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("mcms");
        $this->setSource("member");
        $this->hasMany('id', 'Mcms\Models\Album', 'createdBy', ['alias' => 'AlbumsCreated']);
        $this->hasMany('id', 'Mcms\Models\Album', 'updatedBy', ['alias' => 'AlbumsUpdated']);
        $this->hasMany('id', 'Mcms\Models\AlbumImage', 'createdBy', ['alias' => 'AlbumImagesCreated']);
        $this->hasMany('id', 'Mcms\Models\Article', 'createdBy', ['alias' => 'ArticlesCreated']);
        $this->hasMany('id', 'Mcms\Models\Article', 'updatedBy', ['alias' => 'ArticlesUpdated']);
        $this->hasMany('id', 'Mcms\Models\Comment', 'createdBy', ['alias' => 'CommentsCreated']);
        $this->hasMany('id', 'Mcms\Models\Comment', 'updatedBy', ['alias' => 'CommentsUpdated']);
        $this->hasMany('id', 'Mcms\Models\Image', 'createdBy', ['alias' => 'ImagesCreated']);
        $this->hasMany('id', 'Mcms\Models\Image', 'updatedBy', ['alias' => 'ImagesUpdated']);
        $this->hasMany('id', 'Mcms\Models\Member', 'createdBy', ['alias' => 'MembersCreated']);
        $this->hasMany('id', 'Mcms\Models\Member', 'updatedBy', ['alias' => 'MembersUpdated']);
        $this->hasMany('id', 'Mcms\Models\Message', 'createdBy', ['alias' => 'MessagesCreated']);
        $this->hasMany('id', 'Mcms\Models\Message', 'updatedBy', ['alias' => 'MessagesUpdated']);
        $this->hasMany('id', 'Mcms\Models\Option', 'updatedBy', ['alias' => 'OptionsUpdated']);
        $this->hasMany('id', 'Mcms\Models\Page', 'createdBy', ['alias' => 'PagesCreated']);
        $this->hasMany('id', 'Mcms\Models\Page', 'updatedBy', ['alias' => 'PagesUpdated']);
        $this->hasMany('id', 'Mcms\Models\SpecialPage', 'updatedBy', ['alias' => 'SpecialPagesUpdated']);
        $this->belongsTo('profilePicture', 'Mcms\Models\\Image', 'id', ['alias' => 'ProfilePicture']);
        $this->belongsTo('createdBy', 'Mcms\Models\\Member', 'id', ['alias' => 'createdByMember']);
        $this->belongsTo('updatedBy', 'Mcms\Models\\Member', 'id', ['alias' => 'updatedByMember']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Member[]|Member|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Member|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }


    public function getFullname()
    {
        return $this->firstname . " " . $this->lastname;
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'member';
    }

}
