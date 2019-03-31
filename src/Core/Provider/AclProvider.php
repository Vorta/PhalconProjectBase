<?php

namespace Project\Core\Provider;

use Phalcon\DiInterface;
use Project\Core\Security\Acl;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class AclProvider
 * @package Project\Core\Provider
 */
class AclProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('acl', Acl::class);
    }
}
