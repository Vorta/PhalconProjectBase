<?php

namespace Project\CLI;

use LogicException;
use Phalcon\DiInterface;
use Phalcon\DispatcherInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Cli\Dispatcher as CliDispatcher;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;

/**
 * Class Module
 * @package Project\CLI
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
//            'Project\CLI\Task'
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
                $dispatcher->setDefaultNamespace('Project\CLI\Controllers');
                break;

            case $dispatcher instanceof CliDispatcher:
                $dispatcher->setDefaultNamespace('Project\CLI\Task');
                break;

            default:
                throw new LogicException("Unexpected dispatcher variant");
        }
    }
}
