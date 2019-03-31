<?php

namespace Project\Core\Provider;

use Phalcon\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Events\Manager as EventsManager;

/**
 * Class EventsManagerProvider
 * @package Project\Core\Provider
 */
class EventsManagerProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('eventsManager', EventsManager::class);
    }
}
