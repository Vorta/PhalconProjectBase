<?php

namespace Project\Core\Provider;

use Phalcon\Mvc\Router;
use Phalcon\DiInterface;
use Phalcon\Config\Adapter\Yaml;
use Phalcon\Di\ServiceProviderInterface;
use Project\Core\Component\Translator;

/**
 * Class RouterProvider
 * @package Project\Core\Provider
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class RouterProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('router', function () use ($di) {
            $router = new Router(false);
            $router->removeExtraSlashes(true);

            $router->setDefaults([
                'module'        => 'front',
                'controller'    => 'index',
                'action'        => 'index'
            ]);

            $routes = (new Yaml(project_root('/config/routes.yaml')))->toArray();

            /**
             * Resolve language if it wasn't preconfigured
             */
            $prefix = '';
            if (!isset($_SERVER['LANG'])) {
                // Extracting language from the URI
                preg_match(
                    '/^(\/([a-z]{2}))(\/|$)/i',
                    $router->getRewriteUri(),
                    $output
                );

                $prefix = $output[1] ?? null;
                $_SERVER['LANG'] = $output[2] ?? null;

                $router->add('/', [
                    'controller'    => 'error',
                    'action'        => 'languageUndefined'
                ]);
            }

            if (!is_null($_SERVER['LANG'])) {
                /** @var Translator $translator */
                $translator = $di->get('translator', ['lang' => $_SERVER['LANG']]);
                /**
                 * Here we load the routes into the router
                 * @var string $routeName
                 * @var array $config
                 */
                foreach ($routes as $routeName => $config) {
                    $pattern = $prefix . ($translator->route($routeName) ?? $config['route']);

                    $route = new Router\Route(
                        preg_replace('/(?!^)\/$/', '', $pattern),
                        $config['resources']
                    );

                    if (isset($config['methods'])) {
                        $route->via($config['methods']);
                    }

                    $route->setName($routeName);

                    // Add the route
                    $router->attach($route);
                }
            }

            $router->notFound([
                'controller'    => 'error',
                'action'        => 'show404'
            ]);

            return $router;
        });
    }
}
