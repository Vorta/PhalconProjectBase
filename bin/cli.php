<?php

use Phalcon\Cli\Console;
use Phalcon\Di\FactoryDefault\Cli as CliDi;

define('PROJECT_ROOT', realpath(dirname(__DIR__)));
define('DEBUG', file_exists(PROJECT_ROOT .'/.debug'));

try {

    if (DEBUG) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    $di = new CliDi();
    $di->get('dispatcher')->setDefaultNamespace("Project\CLI\Task");

    // Read the loader
    include PROJECT_ROOT .'/config/loader.php';

    // Read the services
    //include PROJECT_ROOT .'/config/services.cli.php';

    $console = new Console();
    $console->setDI($di);

    /**
     * Process the console arguments
     */
    $arguments = [];

    foreach ($argv as $k => $arg) {
        if ($k === 1) {
            $arguments['task'] = $arg;
        } elseif ($k === 2) {
            $arguments['action'] = $arg;
        } elseif ($k >= 3) {
            $arguments['params'][] = $arg;
        }
    }

    $console->handle($arguments);

} catch (\Phalcon\Exception $e) {
    var_dump($e);
    echo "Error: ". $e->getMessage() . PHP_EOL;
} catch (\Throwable $throwable) {
    echo "Error: ". $throwable->getMessage() . PHP_EOL;
    /*$di->get('logger')->error(
        $e->getMessage()
        ."; File: ". $e->getFile()
        ."; Line: ". $e->getLine()
    );*/
}
