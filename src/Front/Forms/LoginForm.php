<?php

namespace Project\Front\Forms;

use Phalcon\Security;
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class LoginForm
 * @package Project\Front\Forms
 * @property Security $security
 */
class LoginForm extends Form
{
    /**
     *
     */
    public function initialize(): void
    {
        // Username
        $username = new Text('username');
        $username->setLabel('Username');
        $username->addValidator(
            new PresenceOf([
                'message' => 'Username is required!'
            ])
        );

        $this->add($username);

        // Password
        $password = new Password('password');
        $password->setLabel('Password');
        $password->addValidator(
            new PresenceOf([
                'message' => 'The password is required!'
            ])
        );
        $password->clear();

        $this->add($password);

        // CSRF
        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical([
            'value' => $this->security->getSessionToken(),
            'message' => 'CSRF validation failed'
        ]));
        $csrf->clear();

        $this->add($csrf);
    }
}
