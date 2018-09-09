<?php

namespace Project\Front\Controllers;

use Project\Core\Security\Acl;
use Project\Core\Security\Auth;
use Project\Core\Security\Identity;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;

/**
 * Class ControllerBase
 * @package Project\Front\Controllers
 * @property Auth auth
 * @property Acl acl
 */
class ControllerBase extends Controller
{
    /**
     * @param Dispatcher $dispatcher
     * @return bool
     */
    public function beforeExecuteRoute(Dispatcher $dispatcher): bool
    {
        $controllerName = $dispatcher->getControllerName();
        $actionName = $dispatcher->getActionName();

        $this->t = $this->di->getTranslator();
        $this->view->t = $this->t;

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
