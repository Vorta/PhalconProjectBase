<?php

namespace Project\Front;

use Phalcon\Mvc\View;
use Phalcon\DiInterface;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;

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
        // TODO: Implement registerAutoloaders() method.
    }

    /**
     * Registers services related to the module
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di)
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = $di->get('dispatcher');
        $dispatcher->setDefaultNamespace('Project\Front\Controllers');

        /** @var View $view */
        $view = $di->get('view');
        $view->setViewsDir(__DIR__ . '/Views/');
    }
}
