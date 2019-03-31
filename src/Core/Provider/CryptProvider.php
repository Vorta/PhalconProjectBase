<?php

namespace Project\Core\Provider;

use Phalcon\Crypt;
use Phalcon\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class CryptProvider
 * @package Project\Core\Provider
 */
class CryptProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('crypt', function () use ($di) {
            $config = $di->get('config');

            $crypt = new Crypt();
            $crypt->setKey($config->application->cryptSalt);
            return $crypt;
        });
    }
}
