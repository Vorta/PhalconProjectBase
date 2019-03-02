<?php

namespace Project\Core\Security;

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
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
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
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list(
            $this->userId,
            $this->username,
            $this->roles,
            $this->createdAt
            ) = unserialize($serialized);
    }
}
