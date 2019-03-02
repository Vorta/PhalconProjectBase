<?php

namespace Project\Front\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Email as EmailValidator;

/**
 * Class RegisterForm
 * @package Project\Front\Forms
 */
class RegisterForm extends Form
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

        // Email
        $email = new Email('email');
        $email->setLabel('E-mail');
        $email->addValidator(new PresenceOf([
            'message' => 'E-mail is required!'
        ]));
        $email->addValidator(new EmailValidator([
            'message' => 'The e-mail is not valid!'
        ]));

        $this->add($email);

        // Password
        $password = new Password('password');
        $password->setLabel('Password');
        $password->addValidator(
            new PresenceOf([
                'message' => 'The password is required!'
            ])
        );
        $password->addValidator(
            new StringLength([
                'min' => 8,
                'messageMinimum' => 'Password is too short. Please use 8 or more characters.'
            ])
        );
        $password->addValidator(
            new Confirmation([
                'message' => 'Password and confirmation do not match!',
                'with' => 'confirmPassword'
            ])
        );
        $password->clear();

        $this->add($password);

        // Confirm Password
        $confirmPassword = new Password('confirmPassword');
        $confirmPassword->setLabel('Confirm Password');
        $confirmPassword->addValidator(
            new PresenceOf([
                'message' => 'The confirmation password is required'
            ])
        );
        $confirmPassword->clear();

        $this->add($confirmPassword);

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
