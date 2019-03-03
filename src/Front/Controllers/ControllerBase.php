<?php

namespace Project\Front\Controllers;

use Phalcon\DiInterface;
use Phalcon\FlashInterface;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ViewInterface;
use Project\Core\Security\Acl;
use Project\Core\Security\Auth;
use Phalcon\Http\ResponseInterface;
use Project\Core\Security\Identity;
use Phalcon\Translate\Adapter\NativeArray;

/**
 * Class ControllerBase
 * @package Project\Front\Controllers
 * @property Acl $acl
 * @property Auth $auth
 * @property DiInterface $di
 * @property ViewInterface $view
 * @property FlashInterface $flash
 * @property ResponseInterface $response
 */
class ControllerBase extends Controller
{
    /**
     * @var NativeArray
     */
    protected $t;

    /**
     * @param Dispatcher $dispatcher
     * @return bool
     */
    public function beforeExecuteRoute(Dispatcher $dispatcher): bool
    {
        $controllerName = $dispatcher->getControllerName();
        $actionName = $dispatcher->getActionName();

        $this->t = $this->di->get('translator');
        $this->view->setVar('t', $this->t);

        if ($this->acl->isPrivate($controllerName, $actionName)) {
            /** @var Identity $identity */
            $identity = $this->auth->getIdentity();

            if (!$identity instanceof Identity) {
                $this->flash->notice('You don\'t have permission to access this page! Log in first!');

                $this->response->redirect('/');
                return false;
            }

            if (!$this->acl->isAllowed($identity->getRoles(), $controllerName, $actionName)) {
                $this->flash->notice('You don\'t have permission to access this page!');

                $this->response->redirect('/');
                return false;
            }
        }

        return true;
    }
}
