<?php

namespace Project\Front\Forms;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Project\Core\Forms\AbstractForm;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class LoginForm
 * @package Project\Front\Forms
 */
class LoginForm extends AbstractForm
{
    /**
     * Initialization of the login form
     */
    public function initialize(): void
    {
        $this->title        = translate('LBL_LOGIN');
        $this->submitText   = translate('LBL_LOGIN_BTN');

        // Username
        $username = new Text('username');
        $username->setLabel(translate('LBL_USERNAME'));
        $username->addValidator(
            new PresenceOf([
                'message' => translate('ERR_USERNAME_REQUIRED')
            ])
        );

        $this->add($username);

        // Password
        $password = new Password('password');
        $password->setLabel(translate('LBL_PASSWORD'));
        $password->addValidator(
            new PresenceOf([
                'message' => translate('ERR_PASSWORD_REQUIRED')
            ])
        );
        $password->clear();

        $this->add($password);

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
