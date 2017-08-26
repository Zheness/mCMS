<?php
namespace Mcms\Modules\Admin;

use Mcms\Library\DispatchPlugin;
use Phalcon\DiInterface;
use Phalcon\Events\Manager;
use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{
    /**
     * Registers an autoloader related to the module
     *
     * @param DiInterface $di
     */
    public function registerAutoloaders(DiInterface $di = null)
    {
        $loader = new Loader();

        $loader->registerNamespaces([
            'Mcms\Modules\Admin\Controllers' => __DIR__ . '/controllers/',
            'Mcms\Modules\Admin\Forms' => __DIR__ . '/forms/'
        ]);

        $loader->register();
    }

    /**
     * Registers services related to the module
     *
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di)
    {
        /**
         * Setting up the view component
         */
        $di->set('view', function () {
            $view = new View();
            $view->setDI($this);
            $view->setViewsDir(__DIR__ . '/views/');

            $view->registerEngines([
                '.volt' => 'voltShared',
                '.phtml' => PhpEngine::class
            ]);

            return $view;
        });

        /**
         * Setting up the url service
         */
        $di->set('url', function () {
            $url = new Url();
            $url->setBaseUri("/admin/");
            $url->setStaticBaseUri("/");
            return $url;
        });

        /**
         * Setting up the dispatcher service
         */
        $di->set('dispatcher', function () {
            $eventsManager = new Manager();
            $eventsManager->attach(
                "dispatch",
                new DispatchPlugin()
            );

            $dispatcher = new Dispatcher();

            $dispatcher->setEventsManager($eventsManager);

            return $dispatcher;
        });
    }
}
