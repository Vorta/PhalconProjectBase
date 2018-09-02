<?php

$application->registerModules([
    'front'  => [
        'className' => Project\Front\Module::class,
        'path'      => PROJECT_ROOT . '/src/Front/Module.php'
    ]
]);
