<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Models\Album;
use Mcms\Models\Image;
use Mcms\Models\Page;

/**
 * Class ImageAjaxController
 * @Private
 * @Admin
 * @Ajax
 */
class ImageAjaxController extends ControllerBase
{
    private $draw;
    private $columns;
    private $order;
    private $start;
    private $length;
    private $search;

    /**
     * List of the pages
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
            "id" => "i.id",
            "thumbnail" => "1",
            "title" => "i.title",
            "creation" => "i.dateCreated",
            "edition" => "i.dateUpdated",
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
        $query->from(["i" => "Mcms\\Models\\Image"]);
        $query->leftJoin("Mcms\\Models\\Member", "i.createdBy = mc.id", "mc");
        $query->leftJoin("Mcms\\Models\\Member", "i.updatedBy = mu.id", "mu");
        if (strlen($this->search)) {
            if (is_numeric($this->search)) {
                $query->where("i.id LIKE :search:", ["search" => $this->search]);
            } else {
                $query->where("i.title LIKE :search:", ["search" => "%" . $this->search . "%"]);
                $query->orWhere("CONCAT(mc.firstname, ' ', mc.lastname) LIKE :search:", ["search" => "%" . $this->search . "%"]);
                $query->orWhere("CONCAT(mu.firstname, ' ', mu.lastname) LIKE :search:", ["search" => "%" . $this->search . "%"]);
            }
        }

        $imagesCount = count($query->getQuery()->execute());

        $query->orderBy($allowedColumns[$this->columns[$this->order[0]["column"]]["name"]] . " " . $this->order[0]["dir"]);
        $query->limit($this->length, $this->start);

        $images = $query->getQuery()->execute();
        $data = [];
        foreach ($images as $image) {
            /** @var Image $image */
            $data[] = [
                $image->id,
                '<img src="' . $image->getThumbnailUrl() . '" class="thumbnail-datatables">',
                $image->title,
                $image->dateCreatedToFr() . "<br/>" . $image->getAdminLinkCreator(),
                $image->dateUpdatedToFr() . "<br/>" . $image->getAdminLinkLastEditor(),
                '<div class="btn-group btn-group-sm btn-group-right">
                     <a href="' . $this->url->get("image/edit/" . $image->id) . '" class="btn btn-default"><span class="fa fa-pencil"></span> Modifier</a>
                     <a href="' . $this->url->get("image/delete/" . $image->id) . '" class="btn btn-outline btn-danger"><span class="fa fa-trash"></span> Supprimer</a>
                </div>',
            ];
        }

        $response = [
            "draw" => $this->draw,
            "recordsTotal" => Image::count(),
            "recordsFiltered" => $imagesCount,
            "data" => $data
        ];
        return $this->response->setJsonContent($response);
    }

    public function thumbnailsAction()
    {
        $limit = (int) $this->request->getQuery('limit', 'int', 9);
        $limit = $limit < 0 ? 9 : $limit;
        $offset = (int) $this->request->getQuery('offset', 'int', 0);
        $offset = $offset < 0 ? 0 : $offset;

        $images = Image::find([
            'limit' => $limit,
            'offset' => $offset,
        ]);

        $data = [];
        foreach ($images as $image) {
            $data[] = [
                "id" => $image->id,
                "title" => $image->title,
                "description" => $image->description,
                "filename" => $image->filename,
                "thumbnailUrl" => $image->getThumbnailUrl(),
            ];
        }

        $response = [
            "limit" => $limit,
            "offset" => $offset,
            "recordsTotal" => Image::count(),
            "data" => $data,
        ];
        return $this->response->setJsonContent($response);
    }
}

