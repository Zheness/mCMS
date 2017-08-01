<?php

use Phalcon\Loader;

$loader = new Loader();

/**
 * Register Namespaces
 */
$loader->registerNamespaces([
    'Mcms\Models' => APP_PATH . '/common/models/',
    'Mcms'        => APP_PATH . '/common/library/',
]);

/**
 * Register module classes
 */
$loader->registerClasses([
    'Mcms\Modules\Frontend\Module' => APP_PATH . '/modules/frontend/Module.php',
    'Mcms\Modules\Admin\Module' => APP_PATH . '/modules/admin/Module.php',
    'Mcms\Modules\Cli\Module'      => APP_PATH . '/modules/cli/Module.php'
]);

$loader->register();
