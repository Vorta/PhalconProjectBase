<?php

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Router\Group as RouterGroup;

/**
 * Router definition
 */
$router = new Router(false);
$router->removeExtraSlashes(true);

$router->setDefaults([
    'module'        => 'front',
    'controller'    => 'index',
    'action'        => 'index'
]);

/**
 * Resolve language
 */
preg_match(
    "/^\/([a-z]{2})(\/|$)/",
    $router->getRewriteUri(),
    $output);

$lang = $output[1] ?? null;

/**
 * Load routes if language exists.
 * We don't need to waste time if there is no lang in URI
 */
if (!is_null($lang)) {
    $di->setShared('lang', function () use ($lang) {
        $config = $this->getConfig();
        return file_exists($config->translations->directory ."/$lang.php")
            ? $lang
            : $config->translations->defaultLang;
    });

    /**
     * Load the lang-related routes if requested language existed
     */
    if ($di->getLang() === $lang) {
        $translation = $di->getTranslation();

        $routeGroup = new RouterGroup();
        $routeGroup->setPrefix('/'. $lang);

        $routes = include __DIR__ . '/routes.php';

        /**
         *  Here we load the routes into the router
         *  Route translation also happens here
         */
        foreach ($routes as $routeName => $data) {
            $routeGroup
                ->add(
                    $translation['routes'][$routeName] ?? $data['route'],
                    $data['resources']
                )
                ->setName($routeName);
        }

        $router->mount($routeGroup);
    }
}

/**
 * This will direct us to the default language on the first visit of the domain
 */
$router->add('/', [
    'controller'    => 'error',
    'action'        => 'languageUndefined'
]);

$router->notFound([
    'controller'    => 'error',
    'action'        => 'show404'
]);

return $router;
