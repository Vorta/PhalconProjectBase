<?php

namespace Project\Core\Plugin;

use Phalcon\Events\Event;
use Phalcon\FlashInterface;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;
use Project\Core\Security\Acl;
use Project\Core\Security\Auth;
use Phalcon\Http\ResponseInterface;
use Project\Core\Security\Identity;

/**
 * Class SecurityPlugin
 * @package Project\Core\Plugin
 * @property Acl $acl
 * @property Auth $auth
 * @property FlashInterface $flash
 * @property ResponseInterface $response
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class SecurityPlugin extends Plugin
{
    /**
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return bool
     */
    public function beforeExecuteRoute(
        Event $event,
        Dispatcher $dispatcher
    ): bool {
        $controllerName = $dispatcher->getControllerName();
        $actionName = $dispatcher->getActionName();

        if ($this->acl->isPrivate($controllerName, $actionName)) {
            /** @var Identity $identity */
            $identity = $this->auth->getIdentity();

            if (!$identity instanceof Identity) {
                $this->flash->notice('You don\'t have permission to access this page! Log in first!');

                $this->response->redirect('');
                return false;
            }

            if (!$this->acl->isAllowed($identity->getRoles(), $controllerName, $actionName)) {
                $this->flash->notice('You don\'t have permission to access this page!');

                $this->response->redirect('');
                return false;
            }
        }

        return true;
    }
}
