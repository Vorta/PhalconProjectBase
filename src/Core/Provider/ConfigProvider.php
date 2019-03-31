<?php

namespace Project\Core\Provider;

use Phalcon\DiInterface;
use Phalcon\Config\Adapter\Yaml;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class ConfigProvider
 * @package Project\Core\Provider
 */
class ConfigProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('config', function () {
            $config = new Yaml(project_root('config/config.yaml'));
            return $config;
        });
    }
}
