<?php

namespace Project\Front\Controllers;

use Phalcon\FlashInterface;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\ViewInterface;
use Project\Core\Security\Auth;
use Phalcon\Http\RequestInterface;
use Phalcon\Http\ResponseInterface;

/**
 * Class IndexController
 * @package Project\Front\Controllers
 * @property Auth $auth
 * @property ViewInterface $view
 * @property FlashInterface $flash
 * @property RequestInterface $request
 * @property ResponseInterface $response
 */
class IndexController extends Controller
{
    /**
     * Default homepage
     */
    public function indexAction()
    {
        // This should load index.volt
    }
}
