<?php

namespace Project\Front\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Project\Core\Forms\AbstractForm;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Email as EmailValidator;

/**
 * Class RegisterForm
 * @package Project\Front\Forms
 */
class RegisterForm extends AbstractForm
{
    /**
     * Initialization of the registration form
     */
    public function initialize(): void
    {
        $this->title        = translate('LBL_REGISTRATION');
        $this->submitText   = translate('LBL_REGISTRATION_BTN');

        // Username
        $username = new Text('username');
        $username->setLabel(translate('LBL_USERNAME'));
        $username->addValidator(
            new PresenceOf([
                'message' => translate('ERR_USERNAME_REQUIRED')
            ])
        );

        $this->add($username);

        // Email
        $email = new Email('email');
        $email->setLabel(translate('LBL_EMAIL'));
        $email->addValidator(new PresenceOf([
            'message' => translate('ERR_EMAIL_REQUIRED')
        ]));
        $email->addValidator(new EmailValidator([
            'message' => translate('ERR_EMAIL_INVALID')
        ]));

        $this->add($email);

        // Password
        $password = new Password('password');
        $password->setLabel(translate('LBL_PASSWORD'));
        $password->addValidator(
            new PresenceOf([
                'message' => translate('ERR_PASSWORD_REQUIRED')
            ])
        );
        $password->addValidator(
            new StringLength([
                'min' => 8,
                'messageMinimum' => translate(
                    'ERR_PASSWORD_LENGTH',
                    ['length' => 8]
                )
            ])
        );
        $password->addValidator(
            new Confirmation([
                'message' => translate('ERR_PASSWORD_CONFIRM_MATCH'),
                'with' => 'confirmPassword'
            ])
        );
        $password->clear();

        $this->add($password);

        // Confirm Password
        $confirmPassword = new Password('confirmPassword');
        $confirmPassword->setLabel(translate('LBL_PASSWORD_CONFIRM'));
        $confirmPassword->addValidator(
            new PresenceOf([
                'message' => translate('ERR_PASSWORD_CONFIRM_REQUIRED')
            ])
        );
        $confirmPassword->clear();

        $this->add($confirmPassword);

        // CSRF
        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical([
            'value' => $this->security->getSessionToken(),
            'message' => translate('ERR_CSRF_FAILED')
        ]));
        $csrf->setUserOption('special-element', 'hidden');
        $csrf->clear();

        $this->add($csrf);
    }
}
