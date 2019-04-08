<?php

namespace Project\Core\Provider;

use Phalcon\DiInterface;
use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\Mvc\ViewBaseInterface;
use Phalcon\Di\ServiceProviderInterface;
use Project\Core\Component\VoltFunctionsExtension;

/**
 * Class VoltProvider
 * @package Project\Core\Provider
 */
class VoltProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('volt', function (ViewBaseInterface $view) use ($di) {
            $di->get('logger')->info('Initializing Volt...');
            $config = $di->get('config');

            $volt = new Volt($view, $di);

            $pathToCompiled = project_root($config->application->cacheDir . 'volt/');

            if (!file_exists($pathToCompiled)) {
                mkdir($pathToCompiled, 0777, true);
            }

            $volt->setOptions([
                'compiledPath'      => $pathToCompiled,
                'compiledSeparator' => '_',
                'compileAlways'     => $di->get('kernel')->isDebug()
            ]);

            $volt->getCompiler()->addExtension(new VoltFunctionsExtension());

            return $volt;
        });
    }
}
