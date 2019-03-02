<?php

namespace Project\Core\Security;

/**
 * Class Role, contains embedded roles
 * @package Project\Core\Models
 */
class Role
{
    public const ANONYMOUS  = 'ANONYMOUS';
    public const ROLE_USER  = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public const ROLE_MAP = [
        self::ANONYMOUS     => 'Anonymous',
        self::ROLE_USER     => 'Default user',
        self::ROLE_ADMIN    => 'Administrator'
    ];

    /**
     * @return array
     */
    public static function getRoles(): array
    {
        try {
            $reflection = new \ReflectionClass(__CLASS__);
            $array = $reflection->getConstants();
        } catch (\ReflectionException $re) {
            $array = [];
        }

        return $array;
    }
}
