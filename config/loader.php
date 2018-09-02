<?php

$loader = new Phalcon\Loader();

$loader->registerNamespaces([
    'Project\Core'               => PROJECT_ROOT .'/src/Core',
    'Project\Core\Models'        => PROJECT_ROOT .'/src/Core/Models/',
    'Project\Front'              => PROJECT_ROOT .'/src/Front',
    'Project\Front\Controllers'  => PROJECT_ROOT .'/src/Front/Controllers/'
]);

$loader->register();

// Composer autoloader
require_once PROJECT_ROOT . '/vendor/autoload.php';