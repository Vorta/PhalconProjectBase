<?php

namespace Project\Front\Controllers;

use Project\Core\Exception\AuthException;
use Project\Core\Models\User;
use Project\Front\Forms\LoginForm;
use Project\Front\Forms\RegisterForm;

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
        $form = new RegisterForm();

        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $user = User::fromRegistration(
                    $this->request->getPost('username'),
                    $this->request->getPost('email'),
                    $this->request->getPost('password')
                );

                if ($user->create()) {
                    $this->flash->success("User created");
                    $this->response->redirect('/');
                    return;
                }

                foreach ($user->getMessages() as $message) {
                    $this->flash->error($message);
                }
            }

            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }
        }

        $this->view->form = $form;
    }

    /**
     * User login
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

                    $this->flash->success("Logged in successfully!");

                    $this->response->redirect('/');
                    return;
                }

                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            }
        } catch (AuthException $authException) {
            $this->flash->error($authException->getMessage());
        }

        $this->view->form = $form;
    }

    /**
     * User logout
     */
    public function logoutAction(): void
    {
        $this->auth->remove();
        $this->flash->success("Logged out!");
        $this->response->redirect('/');
        return;
    }
}
