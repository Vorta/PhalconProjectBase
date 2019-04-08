<?php

namespace Project\Core\Provider;

use Phalcon\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Mvc\Model\MetaData\Redis as MetaDataAdapter;

/**
 * Class ModelsMetadataProvider
 * @package Project\Core\Provider
 */
class ModelsMetadataProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('modelsMetadata', function () use ($di) {
            $di->get('logger')->info('Initializing Models MetaData...');
            $config = $di->get('config');
            return new MetaDataAdapter($config->redis->toArray());
        });
    }
}
