<?php

namespace Project\Core\Provider;

use Phalcon\DiInterface;
use Phalcon\Config\Adapter\Yaml;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class AclResourcesProvider
 * @package Project\Core\Provider
 */
class AclResourcesProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('aclResources', function () {
            return new Yaml(project_root('config/security/access_control.yaml'));
        });
    }
}
