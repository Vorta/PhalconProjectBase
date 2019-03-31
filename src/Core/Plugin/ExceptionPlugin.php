<?php

namespace Project\Core\Plugin;

use Phalcon\Config;
use Phalcon\Dispatcher;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Logger\AdapterInterface;
use Project\Core\Exception\TranslationNotFoundException;
use Phalcon\Mvc\Dispatcher\Exception as DispatcherException;

/**
 * Class ExceptionPlugin - Used to handle exceptions thrown within dispatcher
 * @package Project\Core\Plugin
 * @property Config $config
 * @property AdapterInterface $logger
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class ExceptionPlugin extends Plugin
{
    /**
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @param \Exception $exception
     * @return bool
     * @throws \Phalcon\Exception
     */
    public function beforeException(Event $event, Dispatcher $dispatcher, \Exception $exception)
    {
        // Let's log this exception
        $this->logger->alert(
            get_class($exception) .": "
            . $exception->getMessage()
            ."; File: ". $exception->getFile()
            ."; Line: ". $exception->getLine()
            .";\n". $exception->getTraceAsString()
        );

        $forward = [
            'module'        => 'front',
            'controller'    => 'error',
            'action'        => 'show500'
        ];

        switch ($exception) {
            case $exception instanceof TranslationNotFoundException:
                $forward['action'] = 'show404';
                $forward['params'] = [
                    'lang' => $this->config->get('translations')->defaultLang
                ];
                break;
            case $exception instanceof DispatcherException:
                switch ($exception->getCode()) {
                    case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                    case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                        $forward['action'] = 'show404';
                        break;
                    case Dispatcher::EXCEPTION_CYCLIC_ROUTING:
                        $forward['action'] = 'show400';
                        break;
                    default:
                        $forward['action'] = 'show404';
                }
                break;
            default:
                $forward['action'] = 'show503';
        }

        $dispatcher->forward($forward);
        $this->logger->commit();
        return false;
    }
}
