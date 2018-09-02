<?php

use Phalcon\DI;
use Phalcon\Mvc\Application;
use Fabfuel\Prophiler\Profiler;
use Fabfuel\Prophiler\Plugin\Manager\Phalcon as PluginManager;

define('PROJECT_ROOT', dirname(__DIR__));

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

include PROJECT_ROOT .'/config/dev/debug.php';

// Start dependency injector
$di = new DI();

// Read the loader
include PROJECT_ROOT .'/config/loader.php';

$profiler = new Profiler();

// Read the services
include PROJECT_ROOT .'/config/services.php';

$pluginManager = new PluginManager($profiler);
$pluginManager->register();

// Startup the application
$application = new Application($di);

// Tell her about the modules
include PROJECT_ROOT .'/config/modules.php';

// Engage!
echo $application->handle()->getContent();

$toolbar = new \Fabfuel\Prophiler\Toolbar($profiler);
$toolbar->addDataCollector(new \Fabfuel\Prophiler\DataCollector\Request());
echo $toolbar->render();
