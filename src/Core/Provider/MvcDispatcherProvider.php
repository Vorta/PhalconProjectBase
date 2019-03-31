<?php

namespace Project\Core\Provider;

use Phalcon\DiInterface;
use Phalcon\Mvc\Dispatcher;
use Project\Core\Plugin\SecurityPlugin;
use Phalcon\Di\ServiceProviderInterface;
use Project\Core\Plugin\ExceptionPlugin;
use Phalcon\Events\Manager as EventsManager;

/**
 * Class MvcDispatcherProvider
 * @package Project\Core\Provider
 */
class MvcDispatcherProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('dispatcher', function () use ($di) {
            $dispatcher = new Dispatcher();

            /** @var EventsManager $eventsManager */
            $eventsManager = $di->get('eventsManager');
            $eventsManager->attach(
                'dispatch:beforeException',
                new ExceptionPlugin()
            );
            $eventsManager->attach(
                "dispatch:beforeExecuteRoute",
                new SecurityPlugin()
            );

            $dispatcher->setEventsManager($eventsManager);
            return $dispatcher;
        });
    }
}
