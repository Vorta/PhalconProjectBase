<?php

namespace Project\Core\Security;

/**
 * Class Role, contains embedded roles
 * @package Project\Core\Models
 */
class Role
{
    public const ANONYMOUS      = 'ANONYMOUS';
    public const AUTHENTICATED  = 'AUTHENTICATED';
    public const USER           = 'USER';
    public const ADMIN          = 'ADMIN';

    public const ROLE_MAP = [
        self::ANONYMOUS     => 'Anonymous',
        self::AUTHENTICATED => 'Authenticated',
        self::USER          => 'Default user',
        self::ADMIN         => 'Administrator'
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
