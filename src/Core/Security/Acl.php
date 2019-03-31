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
     * Checks if a resource is considered private
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
     * Checks if the current profile is allowed to access a private resource
     * @param array $userRoles
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    public function isAllowed(array $userRoles, string $controller, string $action): bool
    {
        // Admin can access everything
        if (in_array(Role::ADMIN, $userRoles)) {
            return true;
        }

        /** @var mixed $roleRequired */
        $roleRequired = $this->aclResources->path("$controller.$action")
                        ?? $this->aclResources->path("$controller.*");

        // We expect to get a single string role required and match it against user's roles
        if (is_string($roleRequired)) {
            return in_array($roleRequired, $userRoles);
        }

        // For anything else, there might be a config error
        throw new \LogicException(
            "Route role requirement invalid. Please check access_control.yaml"
        );
    }
}
