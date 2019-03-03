<?php

namespace Project\Core\Models;

use Phalcon\Security;
use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Project\Core\Security\Role;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class User
 * @package Project\Core\Models
 * @property Group|bool $group
 * @method static User|bool findFirstById(int $id)
 * @method static User|bool findFirstByUsername(string $username)
 */
class User extends Model
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var integer
     */
    private $groupId;

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
     * @return array
     */
    public function columnMap(): array
    {
        return [
            'id'        => 'id',
            'username'  => 'username',
            'email'     => 'email',
            'password'  => 'password',
            'group_id'  => 'groupId'
        ];
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

        $validator->add('email', new PresenceOf([
            "message" => "Email is mandatory"
        ]));

        $validator->add('username', new Uniqueness([
            "message" => "This username is already in use"
        ]));

        $validator->add('username', new PresenceOf([
            "message" => "Username is mandatory"
        ]));

        $validator->add('password', new PresenceOf([
            "message" => "Password is mandatory"
        ]));

        return $this->validate($validator);
    }

    /**
     * Sets new password for the user
     * @param string $plainPassword
     */
    public function setPassword(string $plainPassword): void
    {
        /** @var Security $security */
        $security = $this->getDI()->get('security');
        $this->password = $security->hash($plainPassword);
    }

    /**
     * @param string $plainPassword
     * @return bool
     */
    public function checkPassword(string $plainPassword): bool
    {
        /** @var Security $security */
        $security = $this->getDI()->get('security');
        return $security->checkHash($plainPassword, $this->password);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return Group|bool
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Get user's role. Default role is ROLE_USER
     * @return array
     */
    public function getRoles(): array
    {
        if (!$this->group instanceof Group) {
            return [Role::ROLE_USER];
        }
        return $this->group->getRoles();
    }

    /**
     * Handles creation of a new user
     * @param string $username
     * @param string $email
     * @param string $password
     * @return User
     */
    public static function fromRegistration(
        string $username,
        string $email,
        string $password
    ): User {
        $user = new User([
            'username'  => $username,
            'email'     => $email
        ]);
        $user->setPassword($password);

        return $user;
    }
}
