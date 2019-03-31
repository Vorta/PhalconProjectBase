<?php

namespace Project\Core\Security;

use Project\Core\Models\User;

/**
 * Class Identity, stored in session if user is authenticated
 * @package Project\Core\Security
 */
class Identity implements \Serializable
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
     * @var \DateTime
     */
    private $createdAt;

    /**
     * Identity constructor.
     * @param int $userId
     * @param string $username
     * @param array $roles
     * @throws \Exception
     */
    public function __construct(
        int $userId,
        string $username,
        array $roles
    ) {
        $this->userId = $userId;
        $this->username = $username;
        $this->roles = $roles;

        $this->createdAt = new \DateTime();
    }

    /**
     * @param User $user
     * @return Identity
     * @throws \Exception
     */
    public static function fromUser(User $user): self
    {
        return new self(
            $user->getId(),
            $user->getUsername(),
            $user->getRoles()
        );
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
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * String representation of object
     * @return string the string representation of the object or null
     */
    public function serialize(): string
    {
        return serialize([
            $this->userId,
            $this->username,
            $this->roles,
            $this->createdAt
        ]);
    }

    /**
     * Constructs the object
     * @param string $serialized The string representation of the object.
     * @return void
     */
    public function unserialize($serialized): void
    {
        list(
            $this->userId,
            $this->username,
            $this->roles,
            $this->createdAt
        ) = unserialize($serialized);
    }
}
