<?php

namespace Project\Front\Controllers;

/**
 * Class IndexController
 * @package Project\Front\Controllers
 */
class IndexController extends ControllerBase
{
    /**
     * Default homepage
     */
    public function indexAction()
    {
        // This should load index.volt
        echo "You shouldn't see this text";
    }

    /**
     * Only ROLE_USER or higher may access
     */
    public function userOnlyAction()
    {
        echo "You are at least ROLE_USER";
    }

    /**
     * Only ROLE_ADMIN or higher may access
     */
    public function adminOnlyAction()
    {
        echo "You must be ROLE_ADMIN";
    }
}
