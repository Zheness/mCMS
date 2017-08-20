<?php
namespace Mcms\Library;

use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;

class DispatchPlugin extends Plugin
{
    /**
     * This action is executed before execute any action in the application
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return bool
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        $controllerName = $dispatcher->getControllerClass();

        $actionName = $dispatcher->getActiveMethod();

        $controllerIsPrivate = false;
        $classAnnotations = $this->annotations->get($controllerName)->getClassAnnotations();
        if ($classAnnotations !== false) {
            $controllerIsPrivate = $classAnnotations->has("Private");
        }

        $annotations = $this->annotations->getMethod($controllerName, $actionName);

        if ($controllerIsPrivate || $annotations->has("Private")) {
            if (!$this->session->has("member")) {
                $dispatcher->forward(
                    [
                        "controller" => "error",
                        "action" => "error401",
                    ]
                );
                $this->response->setStatusCode(401);
                return false;
            }
        }
        if ($classAnnotations !== false) {
            if ($classAnnotations->has("Admin")) {
                if ($this->session->get("member")->role != 'admin') {
                    $dispatcher->forward(
                        [
                            "controller" => "error",
                            "action" => "error403",
                        ]
                    );
                    $this->response->setStatusCode(403);
                    return false;
                }
            }
        }
        if ($classAnnotations !== false) {
            if ($classAnnotations->has("Ajax")) {
                if (!$this->request->isAjax()) {
                    $dispatcher->forward(
                        [
                            "controller" => "error",
                            "action" => "error404",
                        ]
                    );
                    $this->response->setStatusCode(404);
                    return false;
                }
            }
        }
        return true;
    }

    public function beforeException(Event $event, Dispatcher $dispatcher, \Exception $exception)
    {
        // Handle 404 exceptions
        if ($exception instanceof Dispatcher\Exception) {
            $dispatcher->forward(
                [
                    'controller' => 'error',
                    'action' => 'error404',
                ]
            );
            $this->response->setStatusCode(404);
            return false;
        }

        // Alternative way, controller or action doesn't exist
        switch ($exception->getCode()) {
            case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
            case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                $dispatcher->forward(
                    [
                        'controller' => 'error',
                        'action' => 'error404',
                    ]
                );
                $this->response->setStatusCode(404);
                return false;
                break;
            default:
                var_dump($exception->getMessage());
                exit();
                $dispatcher->forward(
                    [
                        "controller" => "error",
                        "action" => "error500"
                    ]
                );
                $this->response->setStatusCode(500);
                return false;
        }
    }
}
