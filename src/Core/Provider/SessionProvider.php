<?php

namespace Project\Core\Provider;

use Phalcon\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Session\Adapter\Redis as SessionAdapter;

/**
 * Class SessionProvider
 * @package Project\Core\Provider
 */
class SessionProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('session', function () use ($di) {
            $di->get('logger')->info("Session initialized");
            $config = $di->get('config');
            $session = new SessionAdapter($config->redis->toArray());
            $session->start();
            return $session;
        });
    }
}
