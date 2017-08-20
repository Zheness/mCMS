<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Models\Article;

/**
 * Class ArticleAjaxController
 * @Private
 * @Admin
 * @Ajax
 */
class ArticleAjaxController extends ControllerBase
{
    private $draw;
    private $columns;
    private $order;
    private $start;
    private $length;
    private $search;

    /**
     * List of the articles
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
            "publication" => "a.datePublication",
            "creation" => "a.dateCreated",
            "edition" => "a.dateUpdated",
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
        $query->from(["a" => "Mcms\\Models\\Article"]);
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

        $articlesCount = count($query->getQuery()->execute());

        $query->orderBy($allowedColumns[$this->columns[$this->order[0]["column"]]["name"]] . " " . $this->order[0]["dir"]);
        $query->limit($this->length, $this->start);

        $articles = $query->getQuery()->execute();
        $data = [];
        foreach ($articles as $article) {
            /** @var Article $article */
            $data[] = [
                $article->id,
                $article->title,
                $article->datePublicationToFr(),
                $article->dateCreatedToFr() . "<br/>" . $article->getAdminLinkCreator(),
                $article->dateUpdatedToFr() . "<br/>" . $article->getAdminLinkLastEditor(),
                $article->commentsOpen ? '<span class="text-success"><i class="fa fa-check-circle"></i> Oui</span>' : '<span class="text-danger"><i class="fa fa-times-circle"></i> Non</span>',
                $article->isPrivate ? '<span class="text-success"><i class="fa fa-check-circle"></i> Oui</span>' : '<span class="text-danger"><i class="fa fa-times-circle"></i> Non</span>',
                '<div class="btn-group btn-group-sm btn-group-right">
                     <a href="' . $article->getUrl() . '" class="btn btn-default" target="_blank"><span class="fa fa-external-link"></span> Ouvrir la page</a>
                     <a href="' . $this->url->get("article/edit/" . $article->id) . '" class="btn btn-default"><span class="fa fa-pencil"></span> Modifier</a>
                     <a href="' . $this->url->get("article/delete/" . $article->id) . '" class="btn btn-outline btn-danger"><span class="fa fa-trash"></span> Supprimer</a>
                </div>',
            ];
        }

        $response = [
            "draw" => $this->draw,
            "recordsTotal" => Article::count(),
            "recordsFiltered" => $articlesCount,
            "data" => $data
        ];
        return $this->response->setJsonContent($response);
    }
}

