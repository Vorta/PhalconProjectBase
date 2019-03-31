<?php

namespace Project\Core\Provider;

use Phalcon\Escaper;
use Phalcon\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class EscaperProvider
 * @package Project\Core\Provider
 */
class EscaperProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('escaper', Escaper::class);
    }
}
