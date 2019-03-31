<?php

namespace Project\Core\Provider;

use Phalcon\Mvc\View;
use Phalcon\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;

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
            $view = new View();
            $view->setEventsManager($di->get('eventsManager'));

            $view->registerEngines([
                '.volt' => function ($view) use ($di) {
                    $config = $di->get('config');

                    $volt = new VoltEngine($view, $di);

                    $pathToCompiled = project_root($config->application->cacheDir . 'volt/');

                    if (!file_exists($pathToCompiled)) {
                        mkdir($pathToCompiled, 0777, true);
                    }

                    $volt->setOptions([
                        'compiledPath'      => $pathToCompiled,
                        'compiledSeparator' => '_',
                        'compileAlways'     => $di->get('kernel')->isDebug()
                    ]);


                    $volt->getCompiler()->addFunction(
                        't',
                        function ($resolvedArgs) {
                            return '$this->translator->t('. $resolvedArgs .')';
                        }
                    );

                    return $volt;
                }
            ]);

            return $view;
        });
    }
}
