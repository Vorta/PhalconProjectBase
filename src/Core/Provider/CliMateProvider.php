<?php

namespace Project\Core\Provider;

use Phalcon\DiInterface;
use League\CLImate\CLImate;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class CliMateProvider
 * @package Project\Core\Provider
 */
class CliMateProvider implements ServiceProviderInterface
{
    /**
     * Makes pretty console output
     * @link https://github.com/thephpleague/climate
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('output', function () use ($di) {
            $di->get('logger')->info('Initializing CLImate...');
            return new CLImate();
        });
    }
}
