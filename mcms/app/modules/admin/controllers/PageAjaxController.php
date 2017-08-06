<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Library\Tools;
use Mcms\Models\Page;

/**
 * Class PageAjaxController
 * @package Msites\Modules\Admin\Controllers
 * @Private
 * @Ajax
 */
class PageAjaxController extends ControllerBase
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
            "id" => "p.id",
            "title" => "p.title",
            "creation" => "p.dateCreated",
            "edition" => "p.dateUpdated",
            "comments" => "p.commentsOpen",
            "private" => "p.isPrivate",
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
        $query->from(["p" => "Mcms\\Models\\Page"]);
        $query->leftJoin("Mcms\\Models\\Member", "p.createdBy = mc.id", "mc");
        $query->leftJoin("Mcms\\Models\\Member", "p.updatedBy = mu.id", "mu");
        if (strlen($this->search)) {
            if (is_numeric($this->search)) {
                $query->where("p.id LIKE :search:", ["search" => $this->search]);
            } else {
                $query->where("p.title LIKE :search:", ["search" => "%" . $this->search . "%"]);
                $query->orWhere("CONCAT(mc.firstname, ' ', mc.lastname) LIKE :search:", ["search" => "%" . $this->search . "%"]);
                $query->orWhere("CONCAT(mu.firstname, ' ', mu.lastname) LIKE :search:", ["search" => "%" . $this->search . "%"]);
            }
        }

        $pagesCount = count($query->getQuery()->execute());

        $query->orderBy($allowedColumns[$this->columns[$this->order[0]["column"]]["name"]] . " " . $this->order[0]["dir"]);
        $query->limit($this->length, $this->start);

        $pages = $query->getQuery()->execute();
        $data = [];
        foreach ($pages as $page) {
            /** @var Page $page */
            $data[] = [
                $page->id,
                $page->title,
                $page->dateCreatedToFr() . "<br/>" . $page->getAdminLinkCreator(),
                $page->dateUpdatedToFr() . "<br/>" . $page->getAdminLinkLastEditor(),
                $page->commentsOpen ? '<span class="text-success"><i class="fa fa-check-circle"></i> Oui</span>' : '<span class="text-danger"><i class="fa fa-times-circle"></i> Non</span>',
                $page->isPrivate ? '<span class="text-success"><i class="fa fa-check-circle"></i> Oui</span>' : '<span class="text-danger"><i class="fa fa-times-circle"></i> Non</span>',
                '<div class="btn-group btn-group-sm btn-group-right">
                     <a href="' . $page->getUrl() . '" class="btn btn-default" target="_blank"><span class="fa fa-external-link"></span> Ouvrir la page</a>
                     <a href="' . $this->url->get("page/edit/" . $page->id) . '" class="btn btn-default"><span class="fa fa-pencil"></span> Modifier</a>
                     <a href="' . $this->url->get("page/delete/" . $page->id) . '" class="btn btn-outline btn-danger"><span class="fa fa-trash"></span> Supprimer</a>
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
}

