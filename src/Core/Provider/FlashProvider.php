<?php

namespace Project\Core\Provider;

use Phalcon\DiInterface;
use Phalcon\Flash\Session as Flash;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class FlashProvider
 * @package Project\Core\Provider
 */
class FlashProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('flash', function () {
            $flash = new Flash([
                'error'   => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice'  => 'alert alert-info',
                'warning' => 'alert alert-warning'
            ]);

            $flash->setAutoescape(false);

            return $flash;
        });
    }
}
