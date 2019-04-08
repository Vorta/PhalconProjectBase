<?php

namespace Project\Core\Provider;

use Phalcon\DiInterface;
use Project\Core\Security\Auth;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class AuthProvider
 * @package Project\Core\Provider
 */
class AuthProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('auth', function () use ($di) {
            $di->get('logger')->info('Initializing Auth...');
            return new Auth();
        });
    }
}
