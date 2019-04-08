<?php

namespace Project\Core\Provider;

use Phalcon\DiInterface;
use Phalcon\Session\Bag;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class SessionBagProvider
 * @package Project\Core\Provider
 */
class SessionBagProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('sessionBag', function (?string $name) use ($di) {
            $di->get('logger')->info("Initializing Session Bag ($name)...");
            return new Bag($name);
        });
    }
}
