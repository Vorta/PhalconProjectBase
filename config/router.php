<?php

use Phalcon\Mvc\Router;

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
 * Pre-configured language has priority
 */
if (isset($_SERVER['LANG'])) {
    // Using pre-configured language
    $lang = $_SERVER['LANG'];
} else {
    // Using uri language
    preg_match(
        "/^\/([a-z]{2})(\/|$)/",
        $router->getRewriteUri(),
        $output
    );

    $lang = $output[1] ?? null;

    // URI must contain a language, go to error controller if it doesn't
    $router->add('/', [
        'controller'    => 'error',
        'action'        => 'languageUndefined'
    ]);
}

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
     * Load the lang-related routes if requested language can be provided by DI
     */
    if ($di->getLang() === $lang) {
        $translation = $di->getTranslation();

        $routes = include __DIR__ . '/routes.php';

        /**
         *  Here we load the routes into the router
         *  Route translation also happens here
         */
        foreach ($routes as $routeName => $data) {
            // Prefix route with language if URI language is used
            $route = isset($_SERVER['LANG']) ? '' : '/'. $lang;
            // Fetch a proper configured route
            $route .= $translation['routes'][$routeName] ?? $data['route'];
            // Remove trailing slash unless the slash itself is a route
            $route = preg_replace('/(?!^)\/$/', '', $route);

            // Add the route
            $router
                ->add($route, $data['resources'])
                ->setName($routeName);
        }
    }
}

$router->notFound([
    'controller'    => 'error',
    'action'        => 'show404'
]);

return $router;
