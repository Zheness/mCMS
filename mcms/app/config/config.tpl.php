<?php

/*
 * 1. Copy this file in the same folder under the name app_config.php
 * 2. Replace the values by yours
 * 3. That's it!
 */

defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'version' => '1.0',

    'database' => [
        'adapter' => 'Mysql',
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname' => 'mcms',
        'charset' => 'utf8',
    ],

    'site' => [
        'method' => '', // http or https
        'domain' => '', // domain.tld
        'url' => '', // full URL (ex: http://domain.tld)
        'admin_url' => '', // full URL of the administration (ex: http://domain.tld/admin)
        'name' => '', // name of the website
    ],

    'module' => [
        'image' => [
            'maxSize' => '8M', // Maximum size of the image when uploaded (default 8M), false for no limit
            'allowedTypes' => [
                "image/jpeg",
                "image/png",
            ], // Default allowed types of image when uploaded (default image/jpeg and image/png), false or empty array for no restriction
            'maxResolution' => '2000x2000' // Maximum resolution of the image when uploaded (default 2000x2000), false for no limit
        ]
    ],

    'application' => [
        'appDir' => APP_PATH . '/',
        'modelsDir' => APP_PATH . '/common/models/',
        'migrationsDir' => APP_PATH . '/migrations/',
        'cacheDir' => BASE_PATH . '/cache/',

        // This allows the baseUri to be understand project paths that are not in the root directory
        // of the webpspace.  This will break if the public/index.php entry point is moved or
        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
        'baseUri' => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),
    ],

    /**
     * if true, then we print a new line at the end of each CLI execution
     *
     * If we dont print a new line,
     * then the next command prompt will be placed directly on the left of the output
     * and it is less readable.
     *
     * You can disable this behaviour if the output of your application needs to don't have a new line at end
     */
    'printNewLine' => true
]);
