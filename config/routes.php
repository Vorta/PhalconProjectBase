<?php

return [
    'homepage' => [
        'route'     => '/',
        'resources' => [
            'controller'    => 'index',
            'action'        => 'index'
        ]
    ],
    'register' => [
        'route'     => '/register',
        'resources' => [
            'controller'    => 'auth',
            'action'        => 'register'
        ]
    ],
    'login' => [
        'route'     => '/login',
        'resources' => [
            'controller'    => 'auth',
            'action'        => 'login'
        ]
    ],
    'logout' => [
        'route'     => '/logout',
        'resources' => [
            'controller'    => 'auth',
            'action'        => 'logout'
        ]
    ],
    'user' => [
        'route'     => '/user',
        'resources' => [
            'controller'    => 'index',
            'action'        => 'userOnly'
        ]
    ],
    'admin' => [
        'route'     => '/admin',
        'resources' => [
            'controller'    => 'index',
            'action'        => 'adminOnly'
        ]
    ]
];