<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Library\Tools;
use Mcms\Models\Album;
use Mcms\Models\AlbumImage;
use Mcms\Models\Image;
use Mcms\Models\Member;

/**
 * Class PageAjaxController
 * @Private
 * @Admin
 * @Ajax
 */
class MemberAjaxController extends ControllerBase
{
    private $draw;
    private $columns;
    private $order;
    private $start;
    private $length;
    private $search;

    /**
     * List of the members
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
            "id" => "m.id",
            "image" => "1",
            "fullname" => "CONCAT(m.firstname, ' ', m.lastname)",
            "creation" => "m.dateCreated",
            "edition" => "m.dateUpdated",
            "status" => "m.status",
            "role" => "m.role",
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
        $query->from(["m" => "Mcms\\Models\\Member"]);
        $query->leftJoin("Mcms\\Models\\Member", "m.createdBy = mc.id", "mc");
        $query->leftJoin("Mcms\\Models\\Member", "m.updatedBy = mu.id", "mu");
        if (strlen($this->search)) {
            if (is_numeric($this->search)) {
                $query->where("m.id LIKE :search:", ["search" => $this->search]);
            } else {
                $query->where("CONCAT(m.firstname, ' ', m.lastname) LIKE :search:", ["search" => "%" . $this->search . "%"]);
                $query->orWhere("CONCAT(mc.firstname, ' ', mc.lastname) LIKE :search:", ["search" => "%" . $this->search . "%"]);
                $query->orWhere("CONCAT(mu.firstname, ' ', mu.lastname) LIKE :search:", ["search" => "%" . $this->search . "%"]);
            }
        }

        $membersCount = count($query->getQuery()->execute());

        $query->orderBy($allowedColumns[$this->columns[$this->order[0]["column"]]["name"]] . " " . $this->order[0]["dir"]);
        $query->limit($this->length, $this->start);

        $members = $query->getQuery()->execute();
        $data = [];
        foreach ($members as $member) {
            /** @var Member $member */
            $data[] = [
                $member->id,
                $member->profilePicture == null ? '' : '<div class="text-center"><img src="/img/upload/' . $member->ProfilePicture->filename . '" width="100" height="100"></div>',
                $member->getFullname(),
                $member->dateCreatedToFr() . "<br/>" . $member->getAdminLinkCreator(),
                $member->dateUpdatedToFr() . "<br/>" . $member->getAdminLinkLastEditor(),
                Member::getStatusFr($member->status),
                $member->role == 'admin' ? 'Administrateur' : 'Membre',
                '<div class="btn-group btn-group-sm btn-group-right">
                     <a href="' . $this->url->get("member/edit/" . $member->id) . '" class="btn btn-default"><span class="fa fa-pencil"></span> Modifier</a>
                     <a href="' . $this->url->get("member/delete/" . $member->id) . '" class="btn btn-outline btn-danger"><span class="fa fa-trash"></span> Supprimer</a>
                </div>',
            ];
        }

        $response = [
            "draw" => $this->draw,
            "recordsTotal" => Member::count(),
            "recordsFiltered" => $membersCount,
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

