<?php

namespace Project\Core\Provider;

use Phalcon\DiInterface;
use Phalcon\Mvc\Model\Manager;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class ModelsManagerProvider
 * @package Project\Core\Provider
 */
class ModelsManagerProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('modelsManager', Manager::class);
    }
}
