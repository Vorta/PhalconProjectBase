<?php

namespace Project\Core\Provider;

use Phalcon\Tag;
use Phalcon\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class TagProvider
 * @package Project\Core\Provider
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class TagProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('tag', function () {
            Tag::setDoctype(Tag::HTML5);
            return new Tag();
        });
    }
}
