<?php

namespace Project\Core\Security;

use Project\Core\Models\Group;
use Phalcon\Config;
use Phalcon\Mvc\User\Component;
use Phalcon\Acl\Role as AclRole;
use Phalcon\Acl\Resource as AclResource;
use Phalcon\Acl\Adapter\Memory as AclAdapter;

/**
 * Class Acl
 * @package Project\Core\Security
 */
class Acl extends Component
{
    /**
     * The ACL Object
     *
     * @var AclAdapter
     */
    private $acl;

    /**
     * Checks if a controller access is private
     *
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    public function isPrivate(string $controller, string $action): bool
    {
        /** @var Config $resources */
        $resources = $this->getDI()->get('aclResources');

        // If resource is not specified for anonymous access, it is private!
        switch (Role::ANONYMOUS) {
            case $resources->path("$controller.$action"):
            case $resources->path("$controller.*"):
            case $resources->path("*.*"):
                return false;
            default:
                return true;
        }
    }

    /**
     * Checks if the current profile is allowed to access a resource
     *
     * @param string $role
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    public function isAllowed(string $role, string $controller, string $action): bool
    {
        return $this->getAcl()->isAllowed($role, $controller, $action);
    }

    /**
     * Returns the ACL list
     * @return AclAdapter
     */
    public function getAcl(): AclAdapter
    {
        // Check if the ACL is already created
        if (is_object($this->acl)) {
            return $this->acl;
        }

        // Check if ACL exists in session
        if ($this->persistent->has('acl')) {
            $this->acl = $this->persistent->get('acl');
            return $this->acl;
        }

        // Rebuild ACL if none of the above were true
        $this->acl = $this->rebuild();
        return $this->acl;
    }

    /**
     * Rebuilds the access list into a file
     *
     * @return AclAdapter
     */
    public function rebuild(): AclAdapter
    {
        $acl = new AclAdapter();
        $acl->setDefaultAction(\Phalcon\Acl::DENY);

        /** @var Config $resources */
        $resources = $this->getDI()->get('aclResources');

        $roles = Role::getRoles();

        // Register roles
        foreach ($roles as $role) {
            $acl->addRole(new AclRole($role));
        }

        // Register resources
        /**  @var Config $actions */
        foreach ($resources as $resource => $actions) {
            $acl->addResource(new AclResource($resource), array_keys($actions->toArray()));
        }

        /** @var Group $group */
        foreach ($resources as $resource => $actions) {
            foreach ($actions as $action => $role) {
                $acl->allow($role, $resource, $action);
            }
        }

        // Persist it
        $this->persistent->set('acl', $acl);

        return $acl;
    }

    /**
     * Set the acl cache file path
     *
     * @return string
     */
    protected function getFilePath(): string
    {
        if (!isset($this->filePath)) {
            $this->filePath = rtrim($this->config->application->cacheDir, '\\/') . '/acl/data.txt';
        }

        return $this->filePath;
    }

}
