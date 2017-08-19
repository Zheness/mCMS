<?php

namespace Mcms\Models;

use Phalcon\Image\Adapter\Imagick;

class Image extends ModelBase
{

    const NO_IMAGE = "/img/error.png";

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
     * @Column(type="string", length=90, nullable=true)
     */
    public $title;

    /**
     *
     * @var string
     * @Column(type="string", length=90, nullable=false)
     */
    public $filename;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $description;

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("mcms");
        $this->setSource("image");
        $this->hasMany('id', 'Mcms\Models\AlbumImage', 'imageId', ['alias' => 'AlbumImages']);
        $this->hasMany('id', 'Mcms\Models\Member', 'profilePicture', ['alias' => 'ProfilePictureMembers']);
        $this->belongsTo('createdBy', 'Mcms\Models\\Member', 'id', ['alias' => 'createdByMember']);
        $this->belongsTo('updatedBy', 'Mcms\Models\\Member', 'id', ['alias' => 'updatedByMember']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Image[]|Image|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Image|\Phalcon\Mvc\Model\ResultInterface
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
        return 'image';
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        if (file_exists(BASE_PATH . "/public/img/upload/{$this->filename}")) {
            return "/img/upload/{$this->filename}";
        } else {
            return self::NO_IMAGE;
        }
    }

    /**
     * @param int|null $width
     * @param int|null $height
     * @return string
     */
    public function getThumbnailUrl($width = null, $height = null)
    {
        if ($width == null) {
            $width = Option::findFirstBySlug('thumbnail_width')->content;
        }
        if ($height == null) {
            $height = Option::findFirstBySlug('thumbnail_height')->content;
        }
        if (file_exists(BASE_PATH . "/public/img/upload/{$width}/{$height}/{$this->filename}")) {
            return "/img/upload/{$width}/{$height}/{$this->filename}";
        } else {
            if (!file_exists(BASE_PATH . "/public/img/upload/{$this->filename}")) {
                return self::NO_IMAGE;
            }
            if (!file_exists(BASE_PATH . "/public/img/upload/{$width}/{$height}")) {
                if (!mkdir(BASE_PATH . "/public/img/upload/{$width}/{$height}", 0755, true)) {
                    return self::NO_IMAGE;
                }
            }
            $image = new Imagick(BASE_PATH . "/public/img/upload/{$this->filename}");
            $image->resize($width, $height);
            $image->save(BASE_PATH . "/public/img/upload/{$width}/{$height}/{$this->filename}");
            if (file_exists(BASE_PATH . "/public/img/upload/{$width}/{$height}/{$this->filename}")) {
                return "/img/upload/{$width}/{$height}/{$this->filename}";
            } else {
                return self::NO_IMAGE;
            }
        }
    }

}
