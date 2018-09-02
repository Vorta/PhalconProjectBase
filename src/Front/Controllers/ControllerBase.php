<?php

namespace Project\Front\Controllers;

use Project\Core\Security\Identity;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;

class ControllerBase extends Controller
{
    public function beforeExecuteRoute(Dispatcher $dispatcher): bool
    {
        $controllerName = $dispatcher->getControllerName();
        $actionName = $dispatcher->getActionName();

        if ($this->acl->isPrivate($controllerName, $actionName)) {
            /** @var Identity $identity */
            $identity = $this->auth->getIdentity();

            if (!$identity instanceof Identity) {

                $this->flash->notice('You don\'t have permission to access this page! Log in first!');

                $this->response->redirect('/');
                return false;
            }

            if (!$this->acl->isAllowed($identity->getUserRoles(), $controllerName, $actionName)) {
                $this->flash->notice('You don\'t have permission to access this page!');

                $this->response->redirect('/');
                return false;
            }
        }

        return true;
    }
}
