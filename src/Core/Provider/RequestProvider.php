<?php

namespace Project\Core\Provider;

use Phalcon\DiInterface;
use Phalcon\Http\Request;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class RequestProvider
 * @package Project\Core\Provider
 */
class RequestProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('request', Request::class);

        $di->setShared('request', function () use ($di) {
            $di->get('logger')->info('Initializing Request...');
            return new Request();
        });
    }
}
