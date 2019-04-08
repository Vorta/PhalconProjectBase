<?php

namespace Project\Core\Provider;

use LogicException;
use Project\Kernel;
use Phalcon\Mvc\View;
use Phalcon\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Events\Manager as EventsManager;

/**
 * Class ViewProvider
 * @package Project\Core\Provider
 */
class ViewProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('view', function () use ($di) {
            $mode = $di->get('kernel')->getMode();
            /** @var EventsManager $eventsManager */
            $eventsManager = $di->get('eventsManager');

            switch ($mode) {
                case Kernel::MODE_FPM:
                    $di->get('logger')->info('Initializing View...');
                    $view = new View();
                    break;
                case Kernel::MODE_API:
                    $di->get('logger')->info('Initializing API View...');
                    throw new LogicException("Not implemented.");
                case Kernel::MODE_CLI:
                    $di->get('logger')->info('Initializing Simple View...');
                    $view = new View\Simple();
                    break;
                default:
                    throw new LogicException("Tried to start ViewProvider in unrecognized mode.");
            }

            $view->setEventsManager($eventsManager);

            $view->registerEngines([
                '.volt' => $di->get('volt', ['view' => $view])
            ]);

            return $view;
        });
    }
}
