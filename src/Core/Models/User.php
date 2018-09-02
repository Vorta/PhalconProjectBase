<?php

namespace Project\Core\Models;

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;

/**
 * Class User
 * @package Project\Core\Models
 */
class User extends Model
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var integer
     */
    public $groupId;

    /**
     * Model initialization
     */
    public function initialize(): void
    {
        $this->belongsTo(
            'groupId',
            Group::class,
            'id',
            [
                'alias'     => 'group',
                'reusable'  => true
            ]
        );
    }

    /**
     * Validate provided information prior to save
     * @return bool
     */
    public function validation(): bool
    {
        $validator = new Validation();

        $validator->add('email', new Uniqueness([
            "message" => "This email is already in use"
        ]));

        return $this->validate($validator);
    }

    /**
     * Sets new password for the user
     * @param string $plainPassword
     */
    public function setPassword(string $plainPassword): void
    {
        $this->password = $this->security->hash($plainPassword);
    }

    /**
     * @param string $plainPassword
     * @return bool
     */
    public function checkPassword(string $plainPassword): bool
    {
        return $this->security->checkHash($plainPassword, $this->password);
    }
}
