<?php

namespace Project\Core\Provider;

use Phalcon\Security;
use Phalcon\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class SecurityProvider
 * @package Project\Core\Provider
 */
class SecurityProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('security', function () use ($di) {
            $di->get('logger')->info('Initializing Security...');
            $security = new Security();
            $security->setWorkFactor(13);
            return $security;
        });
    }
}
