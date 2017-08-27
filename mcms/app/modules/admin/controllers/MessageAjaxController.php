<?php

namespace Mcms\Modules\Admin\Controllers;

use Mcms\Models\Message;
use Mcms\Models\Page;

/**
 * Class PageAjaxController
 * @Private
 * @Admin
 * @Ajax
 */
class MessageAjaxController extends ControllerBase
{
    private $draw;
    private $columns;
    private $order;
    private $start;
    private $length;
    private $search;

    /**
     * List of the messages
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
            "subject" => "m.subject",
            "creation" => "m.dateCreated",
            "edition" => "m.dateUpdated",
            "read" => "m.unread",
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
        $query->from(["m" => "Mcms\\Models\\Message"]);
        if (strlen($this->search)) {
            if (is_numeric($this->search)) {
                $query->where("m.id LIKE :search:", ["search" => $this->search]);
            } else {
                $query->where("m.subject LIKE :search:", ["search" => "%" . $this->search . "%"]);
                $query->orWhere("CONCAT(m.firstname, ' ', m.lastname) LIKE :search:", ["search" => "%" . $this->search . "%"]);
            }
        }
        $query->andWhere('m.token IS NOT NULL');

        $messagesCount = count($query->getQuery()->execute());

        $query->orderBy($allowedColumns[$this->columns[$this->order[0]["column"]]["name"]] . " " . $this->order[0]["dir"]);
        $query->limit($this->length, $this->start);

        $messages = $query->getQuery()->execute();
        $data = [];
        foreach ($messages as $message) {
            /** @var Message $message */
            $data[] = [
                $message->id,
                $message->subject,
                $message->dateCreatedToFr() . "<br/>" . ($message->createdBy != null ? $message->getAdminLinkCreator() : $message->firstname . " " . $message->lastname),
                $message->dateUpdatedToFr() . "<br/>" . ($message->updatedBy != null ? $message->getAdminLinkLastEditor() : $message->firstname . " " . $message->lastname),
                $message->unread == 0 ? '<span class="text-success"><i class="fa fa-check-circle"></i> Oui</span>' : '<span class="text-danger"><i class="fa fa-times-circle"></i> Non</span>',
                '<div class="btn-group btn-group-sm btn-group-right">
                     <a href="' . $this->url->get("message/thread/" . $message->token) . '" class="btn btn-default"><span class="fa fa-comments"></span> Lire</a>
                     <!--<a href="' . $this->url->get("message/delete/" . $message->token) . '" class="btn btn-outline btn-danger"><span class="fa fa-trash"></span> Supprimer</a>-->
                </div>',
            ];
        }

        $response = [
            "draw" => $this->draw,
            "recordsTotal" => Message::count(['token IS NOT NULL']),
            "recordsFiltered" => $messagesCount,
            "data" => $data
        ];
        return $this->response->setJsonContent($response);
    }
}

