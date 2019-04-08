<?php

namespace Project\Core\Plugin;

use Exception;
use Phalcon\Config;
use Phalcon\Events\Event;
use Phalcon\Cli\Dispatcher;
use League\CLImate\CLImate;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Logger\AdapterInterface;
use Phalcon\Cli\Dispatcher\Exception as DispatcherException;

/**
 * Class CliExceptionPlugin - Used to handle exceptions thrown within dispatcher
 * @package Project\Core\Plugin
 * @property Config $config
 * @property CLImate $output
 * @property AdapterInterface $logger
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class CliExceptionPlugin extends Plugin
{
    /**
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @param Exception $exception
     * @return bool
     */
    public function beforeException(Event $event, Dispatcher $dispatcher, Exception $exception)
    {
        // Let's log this exception
        $this->logger->alert(
            get_class($exception) .": "
            . $exception->getMessage()
            ."; File: ". $exception->getFile()
            ."; Line: ". $exception->getLine()
            .";\n". $exception->getTraceAsString()
        );

        switch ($exception) {
            case $exception instanceof DispatcherException:
                switch ($exception->getCode()) {
                    case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                    case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                        $this->output->error("Requested operation not recognized");
                        break;
                    default:
                        $this->output->error($exception->getMessage());
                }

                break;
            default:
                $this->output->error("Unexpected error. Check log.");
        }

        return false;
    }
}
