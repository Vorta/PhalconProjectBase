<?php

$loader = new Phalcon\Loader();

$loader->registerNamespaces([
    'Project\Core'  => PROJECT_ROOT .'/src/Core',
    'Project\Front' => PROJECT_ROOT .'/src/Front',
    'Project\CLI'   => PROJECT_ROOT .'/src/CLI',
    'Project'       => PROJECT_ROOT .'/src',
])->register();

// Composer autoloader
require_once PROJECT_ROOT . '/vendor/autoload.php';