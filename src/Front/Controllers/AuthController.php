<?php

namespace Project\Front\Controllers;

/**
 * Class AuthController - For registration and authentication
 * @package Project\Front\Controllers
 */
class AuthController extends ControllerBase
{
    /**
     * User registration
     */
    public function registerAction(): void
    {
        echo "Register Action";
    }

    /**
     * User login
     */
    public function loginAction(): void
    {
        echo "Login action";
    }

    /**
     * User logout
     */
    public function logoutAction(): void
    {
        echo "Logout action";
    }
}
