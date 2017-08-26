<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Library\Tools;
use Mcms\Models\Album;
use Mcms\Models\AlbumImage;
use Mcms\Models\Comment;
use Mcms\Models\Image;
use Mcms\Models\Member;

/**
 * Class CommentAjaxController
 * @Private
 * @Admin
 * @Ajax
 */
class CommentAjaxController extends ControllerBase
{
    private $draw;
    private $columns;
    private $order;
    private $start;
    private $length;
    private $search;

    /**
     * List of the comments
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
            "id" => "c.id",
            "author" => "c.dateCreated",
            "comment" => "c.content",
            "location" => "1",
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
        $query->from(["c" => "Mcms\\Models\\Comment"]);
        $query->where("c.parentId IS NULL");
        if (strlen($this->search)) {
            if (is_numeric($this->search)) {
                $query->andWhere("c.id LIKE :search:", ["search" => $this->search]);
            } else {
                $query->andWhere("c.username LIKE :search:", ["search" => "%" . $this->search . "%"]);
            }
        }

        $commentsCount = count($query->getQuery()->execute());

        $query->orderBy($allowedColumns[$this->columns[$this->order[0]["column"]]["name"]] . " " . $this->order[0]["dir"]);
        $query->limit($this->length, $this->start);

        $comments = $query->getQuery()->execute();
        $data = [];
        foreach ($comments as $comment) {
            /** @var Comment $comment */
            $author = $comment->username;
            if ($comment->createdBy != null) {
                $href = $this->getDI()->get("url")->get("member/edit/" . $comment->createdBy);
                $author = "<a href='{$href}'>{$comment->username}</a>";
            }
            $data[] = [
                $comment->id,
                $author . "<br/>" . $comment->dateCreatedToFr(),
                $comment->content,
                $comment->getAdminLinkToElement(),
                '<div class="btn-group btn-group-sm btn-group-right">
                     <a href="' . $this->url->get("comment/reply/" . $comment->id) . '" class="btn btn-default"><span class="fa fa-reply"></span> Répondre</a>
                     <a href="' . $this->url->get("comment/delete/" . $comment->id) . '" class="btn btn-outline btn-danger"><span class="fa fa-trash"></span> Supprimer</a>
                </div>',
            ];
        }

        $response = [
            "draw" => $this->draw,
            "recordsTotal" => Comment::count(['parentId IS NULL']),
            "recordsFiltered" => $commentsCount,
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
            $image = $albumImage->Image;
            $data[] = [
                "id" => $image->id,
                "title" => $image->title,
                "description" => $image->description,
                "filename" => $image->filename,
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

