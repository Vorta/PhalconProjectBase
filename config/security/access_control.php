<?php

use Phalcon\Config;
use Project\Core\Security\Role;

/**
 * Access Control List definition.
 * CASE SENSITIVE!
 */
return new Config([
    'index' => [
        'index'             => Role::ANONYMOUS,
        'userOnly'          => Role::ROLE_USER,
        'adminOnly'         => Role::ROLE_ADMIN,
    ],
    'auth' => [
        'register'          => Role::ANONYMOUS,
        'login'             => Role::ANONYMOUS,
        'forgotPassword'    => Role::ANONYMOUS,
        'resetPassword'     => Role::ANONYMOUS,
        'changePassword'    => Role::ROLE_USER,
        'changeEmail'       => Role::ROLE_USER,
        'logout'            => Role::ROLE_USER
    ],
    'error' => [
        '*'                 => Role::ANONYMOUS
    ],
]);
