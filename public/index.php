<?php

use Phalcon\Di;
use Phalcon\Mvc\Application;
use Fabfuel\Prophiler\Profiler;
use Fabfuel\Prophiler\Plugin\Manager\Phalcon as PluginManager;

define('PROJECT_ROOT', realpath(dirname(__DIR__)));
define('DEBUG', file_exists(PROJECT_ROOT .'/.debug'));

// Check if we're on HTTPS
define(
    'SSL',
    $_SERVER['SERVER_PORT'] == 443 || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
);

// Handle the domain
preg_match(
    "/^[a-z-]+\.([a-z-]+)\.([a-z-.]+)/i",
    $_SERVER['HTTP_HOST'],
    $domain
);
define('DOMAIN', $domain[1]);
define('TLD', $domain[2]);

try {

    if (DEBUG) {
        include PROJECT_ROOT .'/config/dev/debug.php';
    }

    // Start dependency injector
    $di = new Di();

    // Read the loader
    include PROJECT_ROOT .'/config/loader.php';

    if (DEBUG) {
        $profiler = new Profiler();
    }

    // Read the services
    include PROJECT_ROOT .'/config/services.php';

    $di->get('logger')->info('Services injected');

    if (DEBUG) {
        $pluginManager = new PluginManager($profiler);
        $pluginManager->register();
    }

    // Startup the application
    $application = new Application($di);

    // Tell her about the modules
    include PROJECT_ROOT .'/config/modules.php';

    // Engage!
    echo $application->handle()->getContent();

    if (DEBUG) {
        $toolbar = new \Fabfuel\Prophiler\Toolbar($profiler);
        $toolbar->addDataCollector(new \Fabfuel\Prophiler\DataCollector\Request());
        echo $toolbar->render();
    }

} catch (\Exception $e) {
    echo "Error";
    $di->get('logger')->error(
        $e->getMessage()
        ."; File: ". $e->getFile()
        ."; Line: ". $e->getLine()
    );
}

$di->get('logger')->info('Shutdown');
