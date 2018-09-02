<?php

namespace Project\Core\Models;

use Phalcon\Mvc\Model;

/**
 * Class Group
 * @package Project\Core\Models
 */
class Group extends Model
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $name;

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
     * @param string $role
     */
    public function addRole(string $role): void
    {
        if (isset(Role::$role)) {
            $this->roles[] = Role::$role;
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
