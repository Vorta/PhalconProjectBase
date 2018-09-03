<?php
/*
 * Route definition
 */
$router = new Phalcon\Mvc\Router(false);
$router->removeExtraSlashes(true);

$router->setDefaultModule('front');
$router->setDefaultController('index');
$router->setDefaultAction('index');

$router->add('/', [
    'controller'    => 'index',
    'action'        => 'index'
]);

$router->add('/register', [
    'controller'    => 'auth',
    'action'        => 'register'
]);

$router->add('/login', [
    'controller'    => 'auth',
    'action'        => 'login'
]);

$router->add('/logout', [
    'controller'    => 'auth',
    'action'        => 'logout'
]);

$router->add('/user', [
    'controller'    => 'index',
    'action'        => 'userOnly'
]);

$router->add('/admin', [
    'controller'    => 'index',
    'action'        => 'adminOnly'
]);

$router->notFound([
    'controller' => 'error',
    'action'     => 'error404',
]);

return $router;
