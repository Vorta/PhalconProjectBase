<?php

namespace Project\Core\Security;

/**
 * Class Identity, stored in session if user is authenticated
 * @package Project\Core\Security
 */
class Identity
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $username;

    /**
     * @var array
     */
    private $roles;

    /**
     * Identity constructor.
     * @param int $userId
     * @param string $username
     * @param array $roles
     */
    public function __construct(
        int $userId,
        string $username,
        array $roles
    ) {
        $this->userId = $userId;
        $this->username = $username;
        $this->roles = $roles;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return array
     */
    public function getUserRoles(): array
    {
        return $this->roles;
    }
}
