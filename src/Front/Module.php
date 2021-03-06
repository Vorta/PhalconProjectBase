<?php

namespace Project\Front;

use LogicException;
use Phalcon\DiInterface;
use Phalcon\Mvc\ViewInterface;
use Phalcon\DispatcherInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Cli\Dispatcher as CliDispatcher;

/**
 * Class Module
 * @package Project\Front
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class Module implements ModuleDefinitionInterface
{
    /**
     * Registers an autoloader related to the module
     * @param DiInterface $di
     */
    public function registerAutoloaders(DiInterface $di = null)
    {
//        $loader = new Loader();
//
//        $loader->registerNamespaces([
//            'Project\Front\Controllers' => project_root('src/Controllers'),
//            'Project\Front\Models'      => project_root('src/Models'),
//            'Project\Front\Tasks'       => project_root('src/Tasks')
//        ]);
//
//        $loader->register();
    }

    /**
     * Registers services related to the module
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di)
    {
        /** @var DispatcherInterface $dispatcher */
        $dispatcher = $di->get('dispatcher');

        switch ($dispatcher) {
            case $dispatcher instanceof MvcDispatcher:
                $dispatcher->setDefaultNamespace('Project\Front\Controllers');
                break;

            case $dispatcher instanceof CliDispatcher:
                $dispatcher->setDefaultNamespace('Project\Front\Task');
                break;

            default:
                throw new LogicException("Unexpected dispatcher variant");
        }

        /** @var ViewInterface $view */
        $view = $di->get('view');
        $view->setViewsDir(__DIR__ . '/Views/');
    }
}
