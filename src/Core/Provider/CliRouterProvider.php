<?php

namespace Project\Core\Provider;

use Phalcon\Cli\Router;
use Phalcon\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class CliRouterProvider
 * @package Project\Core\Provider
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class CliRouterProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('router', function () use ($di) {
            $di->get('logger')->info('Initializing Cli Router...');
            $router = new Router(false);
            return $router;
        });
    }
}
