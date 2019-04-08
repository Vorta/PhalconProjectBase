<?php

namespace Project\Core\Provider;

use LogicException;
use Project\Kernel;
use Phalcon\DiInterface;
use Project\Core\Plugin\SecurityPlugin;
use Project\Core\Plugin\ExceptionPlugin;
use Phalcon\Di\ServiceProviderInterface;
use Project\Core\Plugin\CliExceptionPlugin;
use Phalcon\Cli\Dispatcher as CliDispatcher;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Events\Manager as EventsManager;

/**
 * Class DispatcherProvider
 * @package Project\Core\Provider
 */
class DispatcherProvider implements ServiceProviderInterface
{
    /**
     * Decides witch Dispatcher variant to provide, depending on the host kernel mode
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('dispatcher', function () use ($di) {
            $mode = $di->get('kernel')->getMode();
            /** @var EventsManager $eventsManager */
            $eventsManager = $di->get('eventsManager');

            switch ($mode) {
                case Kernel::MODE_FPM:
                case Kernel::MODE_API:
                    $di->get('logger')->info('Initializing Mvc Dispatcher...');
                    $dispatcher = new MvcDispatcher();

                    $eventsManager->attach(
                        'dispatch:beforeException',
                        new ExceptionPlugin()
                    );
                    $eventsManager->attach(
                        "dispatch:beforeExecuteRoute",
                        new SecurityPlugin()
                    );
                    break;

                case Kernel::MODE_CLI:
                    $di->get('logger')->info('Initializing Cli Dispatcher...');
                    $dispatcher = new CliDispatcher();

                    $eventsManager->attach(
                        'dispatch:beforeException',
                        new CliExceptionPlugin()
                    );
                    break;

                default:
                    throw new LogicException("Tried to start application in unrecognized mode.");
            }

            $dispatcher->setDI($di);
            $dispatcher->setEventsManager($eventsManager);

            return $dispatcher;
        });
    }
}
