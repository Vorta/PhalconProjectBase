<?php

namespace Project\Core\Provider;

use Phalcon\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Assets\Manager as AssetsManager;

/**
 * Class AssetsProvider
 * @package Project\Core\Provider
 */
class AssetsProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('assets', function () use ($di) {
            $di->get('logger')->info('Initializing Assets...');
            return new AssetsManager();
        });
    }
}
