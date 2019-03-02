<?php

namespace Project\Core\Models;

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Project\Core\Security\Role;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Uniqueness;

/**
 * Class Group
 * @package Project\Core\Models
 */
class Group extends Model
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $roles;

    /**
     * Model initialization
     */
    public function initialize(): void
    {
        $this->hasMany(
            'id',
            User::class,
            'groupId',
            [
                'alias' => 'users',
                'foreignKey' => [
                    'message' => 'Group cannot be deleted while it has users.'
                ]
            ]
        );
    }

    /**
     * @return array
     */
    public function columnMap(): array
    {
        return [
            'id'    => 'id',
            'name'  => 'name',
            'roles' => 'roles'
        ];
    }

    /**
     * Validate provided information prior to save
     * @return bool
     */
    public function validation(): bool
    {
        $validator = new Validation();

        $validator->add('name', new Uniqueness([
            "message" => "A group with this name already exists"
        ]));

        $validator->add('name', new PresenceOf([
            "message" => "Group name is mandatory"
        ]));

        $validator->add('roles', new PresenceOf([
            "message" => "At least one group role is required"
        ]));

        return $this->validate($validator);
    }

    /**
     * Execute before storing to DB
     */
    public function beforeSave(): void
    {
        $this->roles = join(',', $this->roles);
    }

    /**
     * Execute after fetching from DB
     */
    public function afterFetch(): void
    {
        $this->roles = explode(',', $this->roles);
    }

    /**
     * Execute after storing to DB
     */
    public function afterSave(): void
    {
        $this->roles = explode(',', $this->roles);
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $role
     */
    public function addRole(string $role): void
    {
        if (isset(Role::ROLE_MAP[$role])) {
            $this->roles[] = Role::ROLE_MAP[$role];
            $this->roles = array_unique($this->roles);
        }
    }

    /**
     * @param string $role
     */
    public function removeRole(string $role): void
    {
        if ($key = array_search($role, $this->roles) !== false) {
            unset($this->roles[$key]);
        }
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        if (empty($this->roles)) {
            return [Role::ROLE_USER];
        }
        return $this->roles;
    }
}
