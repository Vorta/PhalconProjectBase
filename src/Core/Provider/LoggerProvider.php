<?php

namespace Project\Core\Provider;

use Phalcon\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Logger\Adapter\File as FileLogger;
use Phalcon\Logger\Formatter\Line as FormatterLine;

/**
 * Class LoggerProvider
 * @package Project\Core\Provider
 */
class LoggerProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('logger', function (?string $filename = null, ?string $format = null) use ($di) {
            $config = $di->get('config');

            $format     = $format ?: $config->logger->format;
            $filename   = trim($filename ?: $config->logger->filename, '\\/');
            $path       = project_root('var/log/');

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $formatter  = new FormatterLine($format, $config->logger->date);
            $logger     = new FileLogger($path . $filename);

            $logger->setFormatter($formatter);
            $logger->setLogLevel(constant($config->logger->logLevel));

            $logger->info("Startup");

            return $logger;
        });
    }
}
