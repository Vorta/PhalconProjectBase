<?php

namespace Project\Core\Provider;

use Phalcon\DiInterface;
use Phalcon\Http\Response;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class ResponseProvider
 * @package Project\Core\Provider
 */
class ResponseProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('response', Response::class);
    }
}
