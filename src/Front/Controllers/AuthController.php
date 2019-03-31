<?php

namespace Project\Front\Controllers;

use Phalcon\FlashInterface;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\UrlInterface;
use Project\Core\Models\User;
use Phalcon\Mvc\ViewInterface;
use Project\Core\Security\Auth;
use Project\Front\Forms\LoginForm;
use Phalcon\Http\RequestInterface;
use Phalcon\Http\ResponseInterface;
use Project\Front\Forms\RegisterForm;
use Project\Core\Exception\AuthException;

/**
 * Class AuthController - For registration and authentication
 * @package Project\Front\Controllers
 * @property Auth $auth
 * @property UrlInterface $url
 * @property ViewInterface $view
 * @property FlashInterface $flash
 * @property RequestInterface $request
 * @property ResponseInterface $response
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class AuthController extends Controller
{
    /**
     * User registration
     */
    public function registerAction(): void
    {
        $form = new RegisterForm();

        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $user = User::fromRegistration(
                    $this->request->getPost('username'),
                    $this->request->getPost('email'),
                    $this->request->getPost('password')
                );

                if ($user->create()) {
                    $this->flash->success(
                        translate('MSG_USER_CREATED')
                    );
                    $this->response->redirect(
                        $this->url->get(['for' => 'homepage'])
                    );
                    return;
                }

                foreach ($user->getMessages() as $message) {
                    $this->flash->error($message);
                }
            }
        }

        $this->view->setVar('form', $form);
    }

    /**
     * User login
     * @throws \Exception
     */
    public function loginAction(): void
    {
        $form = new LoginForm();

        try {
            if ($this->request->isPost()) {
                if ($form->isValid($this->request->getPost())) {
                    $this->auth->check(
                        $this->request->getPost('username'),
                        $this->request->getPost('password')
                    );

                    $this->flash->success(
                        translate('MSG_LOG_IN_SUCCESS')
                    );

                    $this->response->redirect(
                        $this->url->get(['for' => 'homepage'])
                    );
                    return;
                }
            }
        } catch (AuthException $authException) {
            $this->flash->error($authException->getMessage());
        }

        $this->view->setVar('form', $form);
    }

    /**
     * User logout
     */
    public function logoutAction(): void
    {
        $this->auth->remove();
        $this->flash->success(
            translate('MSG_LOG_OUT_SUCCESS')
        );
        $this->response->redirect(
            $this->url->get(['for' => 'homepage'])
        );
        return;
    }
}
