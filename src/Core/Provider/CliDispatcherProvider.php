<?php

namespace Project\Core\Provider;

use Phalcon\DiInterface;
use Phalcon\Cli\Dispatcher;
use Phalcon\Di\ServiceProviderInterface;
use Project\Core\Plugin\ExceptionPlugin;
use Phalcon\Events\Manager as EventsManager;

/**
 * Class CliDispatcherProvider
 * @package Project\Core\Provider
 */
class CliDispatcherProvider implements ServiceProviderInterface
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

            $dispatcher->setEventsManager($eventsManager);
            return $dispatcher;
        });
    }
}
