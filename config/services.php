<?php

use Phalcon\Tag;
use Phalcon\Crypt;
use Phalcon\Config;
use Phalcon\Escaper;
use Phalcon\Mvc\View;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Mvc\Dispatcher;
use Project\Core\Security\Acl;
use Project\Core\Security\Auth;
use Phalcon\Flash\Session as Flash;
use Phalcon\Assets\Manager as AssetsManager;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Model\Manager as ModelManager;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Db\Adapter\Pdo\Mysql as DBAdapter;
use Phalcon\Logger\Adapter\File as FileLogger;
use Phalcon\Logger\Formatter\Line as FormatterLine;
use Phalcon\Session\Adapter\Redis as SessionAdapter;
use Phalcon\Mvc\Model\Metadata\Redis as MetaDataAdapter;

/**
 * Register the global configuration
 */
$di->setShared('config', function () {
    $config = include PROJECT_ROOT . '/config/config.php';
    return $config;
});

/**
 * Register the URL component
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new \Phalcon\Mvc\Url();

    $url->setBaseUri($config->application->publicUrl);
    $url->setStaticBaseUri($config->application->staticUrl);

    return $url;
});

/**
 * Prepare the events manager
 */
$di->setShared('eventsManager', function () {
    return new EventsManager();
});

/**
 * Setup the dispatcher
 */
$di->setShared('dispatcher', function () {
    $dispatcher = new Dispatcher();
    $dispatcher->setEventsManager($this->getEventsManager());
    return $dispatcher;
});

/**
 * Loading routes from the routes.php file
 */
$di->set('router', function () {
    return require PROJECT_ROOT . '/config/router.php';
});

/**
 * Setup the view component
 */
$di->setShared('view', function () {
    $view = new View();
    $view->setEventsManager($this->getEventsManager());

    $view->registerEngines([
        '.volt' => function ($view) {
            $config = $this->getConfig();

            $volt = new VoltEngine($view, $this);

            $pathToCompiled = $config->application->cacheDir . 'volt/';

            if (!file_exists($pathToCompiled)) {
                mkdir($pathToCompiled, 0777, TRUE);
            }

            $volt->setOptions([
                'compiledPath'      => $pathToCompiled,
                'compiledSeparator' => '_',
                'compileAlways'     => true
            ]);

            return $volt;
        }
    ]);

    return $view;
});

/**
 * Database connection
 */
$di->set('db', function () {
    $config = $this->getConfig();
    $dbAdapter = new DbAdapter(
        $config->database->toArray()
    );
    $dbAdapter->setEventsManager($this->getEventsManager());
    return $dbAdapter;
});

/**
 * Something to manage those models
 */
$di->set("modelsManager", function () {
    return new ModelManager();
});

/**
 * Storing the model metadata
 */
$di->set('modelsMetadata', function () {
    $config = $this->getConfig();
    return new MetaDataAdapter($config->redis->toArray());
});

/**
 * Session setup
 */
$di->set('session', function () {
    $config = $this->getConfig();
    $session = new SessionAdapter($config->redis->toArray());
    $session->start();
    return $session;
});

/**
 * Flash service with custom CSS classes
 */
$di->set('flash', function () {
    $flash = new Flash([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);

    $flash->setAutoescape(false);

    return $flash;
});

/**
 * Session bag initialization
 */
$di->set('sessionBag', function ($name) {
    return new \Phalcon\Session\Bag($name);
});

/**
 * Crypt service
 */
$di->set('crypt', function () {
    $config = $this->getConfig();

    $crypt = new Crypt();
    $crypt->setKey($config->application->cryptSalt);
    return $crypt;
});

/**
 * Security service
 */
$di->setShared('security', function () {
    $security = new \Phalcon\Security();
    $security->setWorkFactor(13);
    return $security;
});

/**
 * Authentication component
 */
$di->set('auth', function () {
    return new Auth();
});

/**
 * Setup the ACL resources
 */
$di->setShared('aclResources', function() {
    $resources = new Config();
    if (is_readable(PROJECT_ROOT . '/config/security/access_control.php')) {
        $resources = include PROJECT_ROOT . '/config/security/access_control.php';
    }
    return $resources;
});

/**
 * Access Control List
 * Reads privateResource as an array from the config object.
 */
$di->set('acl', function () {
    $acl = new Acl();
    //$acl->addResources($this->getShared('aclResources'));
    return $acl;
});

/**
 * Logger service
 */
$di->set('logger', function ($filename = null, $format = null) {
    $config = $this->getConfig();

    $format     = $format ?: $config->logger->format;
    $filename   = trim($filename ?: $config->logger->filename, '\\/');
    $path       = rtrim($config->logger->path, '\\/') . DIRECTORY_SEPARATOR;

    $formatter  = new FormatterLine($format, $config->logger->date);
    $logger     = new FileLogger($path . $filename);

    $logger->setFormatter($formatter);
    $logger->setLogLevel($config->logger->logLevel);

    return $logger;
});

/**
 * Asset manager
 */
$di->setShared('assets', function () {
    return new AssetsManager();
});

/**
 * Tag service
 */
$di->setShared('tag', function () {
    Tag::setDoctype(Tag::HTML5);
    return new Tag();
});

/**
 * Escaper
 */
$di->set('escaper', function () {
    return new Escaper();
});

/**
 * Http request
 */
$di->set('request', function () {
    return new Request();
}, true);

/**
 * Http response
 */
$di->set('response', function () {
    return new Response();
});

/**
 * Share the profiler
 */
$di->setShared('profiler', $profiler);
