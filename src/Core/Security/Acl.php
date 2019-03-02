<?php

namespace Project\Core\Security;

use Phalcon\Config;
use Phalcon\Mvc\User\Component;

/**
 * Class Acl
 * @package Project\Core\Security
 * @property Config $aclResources
 */
class Acl extends Component
{
    /**
     * Checks if a controller access is private
     *
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    public function isPrivate(string $controller, string $action): bool
    {
        // If resource is not specified for anonymous access, it is private!
        switch (Role::ANONYMOUS) {
            case $this->aclResources->path("$controller.$action"):
            case $this->aclResources->path("$controller.*"):
                return false;
            default:
                return true;
        }
    }

    /**
     * Checks if the current profile is allowed to access a resource
     *
     * @param array $userRoles
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    public function isAllowed(array $userRoles, string $controller, string $action): bool
    {
        if (in_array(Role::ROLE_ADMIN, $userRoles)) {
            return true;
        }

        $roleRequired = $this->aclResources->path("$controller.$action")
                        ?? $this->aclResources->path("$controller.*");

        if (is_null($roleRequired)) {
            return false;
        }

        return $roleRequired === Role::ANONYMOUS
            ? true
            : in_array($roleRequired, $userRoles);
    }
}
