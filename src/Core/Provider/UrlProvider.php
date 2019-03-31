<?php

namespace Project\Core\Provider;

use Phalcon\Mvc\Url;
use Phalcon\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class UrlProvider
 * @package Project\Core\Provider
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class UrlProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('url', function () {
            $url = new Url();

            preg_match(
                "/^[a-z-]+\.([a-z-]+)\.([a-z-.]+)/i",
                $_SERVER['HTTP_HOST'],
                $domain
            );

            $ssl = $_SERVER['SERVER_PORT'] == 443
                   || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

            $url->setBaseUri(
                'http'. ($ssl ? 's' : '') .'://www.'. $domain[1] .'.'. $domain[2] .'/'
            );
            $url->setStaticBaseUri(
                'http'. ($ssl ? 's' : '') .'://static.'. $domain[1] .'.'. $domain[2] .'/'
            );

            return $url;
        });
    }
}
