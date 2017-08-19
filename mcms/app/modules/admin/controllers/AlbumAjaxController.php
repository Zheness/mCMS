<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Library\Tools;
use Mcms\Models\Album;
use Mcms\Models\AlbumImage;
use Mcms\Models\Image;
use Mcms\Models\Page;

/**
 * Class PageAjaxController
 * @Private
 * @Admin
 * @Ajax
 */
class AlbumAjaxController extends ControllerBase
{
    private $draw;
    private $columns;
    private $order;
    private $start;
    private $length;
    private $search;

    /**
     * List of the albums
     */
    public function listAction()
    {
        $response = [
            "draw" => $this->draw,
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => []
        ];
        $this->draw = (int)$this->request->getQuery("draw");
        $this->columns = $this->request->getQuery("columns");
        $this->order = $this->request->getQuery("order");
        $this->start = (int)$this->request->getQuery("start");
        $this->length = (int)$this->request->getQuery("length");
        $this->search = $this->request->getQuery("search");
        $this->search = $this->filter->sanitize($this->search['value'], ["trim", "string"]);

        $allowedColumns = [
            "id" => "a.id",
            "title" => "a.title",
            "creation" => "a.dateCreated",
            "edition" => "a.dateUpdated",
            "images" => "1",
            "comments" => "a.commentsOpen",
            "private" => "a.isPrivate",
            "actions" => "1"
        ];
        $hasErrors = false;
        foreach ($this->columns as $column) {
            if (!key_exists($column["name"], $allowedColumns)) {
                $hasErrors = true;
            }
        }
        if ($hasErrors) {
            return $this->response->setJsonContent($response);
        }

        $query = $this->modelsManager->createBuilder();
        $query->from(["a" => "Mcms\\Models\\Album"]);
        $query->leftJoin("Mcms\\Models\\Member", "a.createdBy = mc.id", "mc");
        $query->leftJoin("Mcms\\Models\\Member", "a.updatedBy = mu.id", "mu");
        if (strlen($this->search)) {
            if (is_numeric($this->search)) {
                $query->where("a.id LIKE :search:", ["search" => $this->search]);
            } else {
                $query->where("a.title LIKE :search:", ["search" => "%" . $this->search . "%"]);
                $query->orWhere("CONCAT(mc.firstname, ' ', mc.lastname) LIKE :search:", ["search" => "%" . $this->search . "%"]);
                $query->orWhere("CONCAT(mu.firstname, ' ', mu.lastname) LIKE :search:", ["search" => "%" . $this->search . "%"]);
            }
        }

        $pagesCount = count($query->getQuery()->execute());

        $query->orderBy($allowedColumns[$this->columns[$this->order[0]["column"]]["name"]] . " " . $this->order[0]["dir"]);
        $query->limit($this->length, $this->start);

        $albums = $query->getQuery()->execute();
        $data = [];
        foreach ($albums as $album) {
            /** @var Album $album */
            $data[] = [
                $album->id,
                $album->title,
                $album->dateCreatedToFr() . "<br/>" . $album->getAdminLinkCreator(),
                $album->dateUpdatedToFr() . "<br/>" . $album->getAdminLinkLastEditor(),
                $album->Images->count(),
                $album->commentsOpen ? '<span class="text-success"><i class="fa fa-check-circle"></i> Oui</span>' : '<span class="text-danger"><i class="fa fa-times-circle"></i> Non</span>',
                $album->isPrivate ? '<span class="text-success"><i class="fa fa-check-circle"></i> Oui</span>' : '<span class="text-danger"><i class="fa fa-times-circle"></i> Non</span>',
                '<div class="btn-group btn-group-sm btn-group-right">
                     <a href="' . $album->getUrl() . '" class="btn btn-default" target="_blank"><span class="fa fa-external-link"></span> Ouvrir l\'album</a>
                     <a href="' . $this->url->get("album/edit/" . $album->id) . '" class="btn btn-default"><span class="fa fa-pencil"></span> Modifier</a>
                     <a href="' . $this->url->get("album/delete/" . $album->id) . '" class="btn btn-outline btn-danger"><span class="fa fa-trash"></span> Supprimer</a>
                </div>',
            ];
        }

        $response = [
            "draw" => $this->draw,
            "recordsTotal" => Page::count(),
            "recordsFiltered" => $pagesCount,
            "data" => $data
        ];
        return $this->response->setJsonContent($response);
    }

    public function thumbnailsAction($id = 0)
    {
        $album = Album::findFirst($id);
        if (!$album) {
            return $this->response->setJsonContent(['code' => 'album_not_found']);
        }

        $data = [];
        foreach ($album->Images as $albumImage) {
            /** @var Image $image */
            $image = $albumImage->Image;
            $data[] = [
                "id" => $image->id,
                "title" => $image->title,
                "description" => $image->description,
                "filename" => $image->filename,
                "thumbnailUrl" => $image->getThumbnailUrl(),
            ];
        }
        return $this->response->setJsonContent($data);
    }

    public function addImageAction($albumId = 0, $imageId = 0)
    {
        $album = Album::findFirst($albumId);
        if (!$album) {
            return $this->response->setJsonContent(['code' => 'album_not_found']);
        }
        $image = Image::findFirst($imageId);
        if (!$image) {
            return $this->response->setJsonContent(['code' => 'image_not_found']);
        }

        $imageInAlbum = AlbumImage::findFirst([
            'albumId = :albumId: AND imageId = :imageId:',
            'bind' => [
                'albumId' => $albumId,
                'imageId' => $imageId,
            ]
        ]);
        if (!$imageInAlbum) {
            $albumImage = new AlbumImage();
            $albumImage->albumId = $albumId;
            $albumImage->imageId = $imageId;
            $albumImage->dateCreated = Tools::now();
            $albumImage->createdBy = $this->session->get('member')->id;
            $albumImage->save();
        }
        return $this->response->setJsonContent(['code' => 'image_added_in_album']);
    }

    public function removeImageAction($albumId = 0, $imageId = 0)
    {
        $album = Album::findFirst($albumId);
        if (!$album) {
            return $this->response->setJsonContent(['code' => 'album_not_found']);
        }
        $image = Image::findFirst($imageId);
        if (!$image) {
            return $this->response->setJsonContent(['code' => 'image_not_found']);
        }

        $imageInAlbum = AlbumImage::findFirst([
            'albumId = :albumId: AND imageId = :imageId:',
            'bind' => [
                'albumId' => $albumId,
                'imageId' => $imageId,
            ]
        ]);
        if ($imageInAlbum) {
            $imageInAlbum->delete();
        }
        return $this->response->setJsonContent(['code' => 'image_removed_from_album']);
    }
}

