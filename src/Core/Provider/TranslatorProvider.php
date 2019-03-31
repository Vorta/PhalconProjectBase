<?php

namespace Project\Core\Provider;

use Phalcon\DiInterface;
use Phalcon\Config\Adapter\Yaml;
use Project\Core\Component\Translator;
use Phalcon\Di\ServiceProviderInterface;
use Project\Core\Exception\TranslationNotFoundException;

/**
 * Class TranslatorProvider
 * @package Project\Core\Provider
 */
class TranslatorProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('translator', function (?string $language) use ($di) {
            $config = $di->get('config');

            // Get language. Fallback to default if language was not received
            $lang = $language ?? $config->translations->defaultLang;

            $path = project_root("config/translations/$lang.yaml");

            if (!file_exists($path)) {
                throw new TranslationNotFoundException($lang);
            }

            return new Translator((new Yaml($path))->toArray());
        });
    }
}
