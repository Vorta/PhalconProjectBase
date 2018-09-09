<?php

namespace Project\Core\Plugin;

use Phalcon\Dispatcher;
use Phalcon\Events\Event;
use Phalcon\Logger\Adapter\File as Logger;
use Phalcon\Mvc\Dispatcher\Exception as DispatcherException;
use Phalcon\Mvc\User\Plugin;
use Project\Core\Exception\TranslationNotFoundException;

/**
 * Class ExceptionPlugin - Used to handle exceptions thrown within dispatcher
 * @package Project\Core\Plugin
 * @property Logger logger
 */
class ExceptionPlugin extends Plugin
{
    public function beforeException(Event $event, Dispatcher $dispatcher, \Exception $exception)
    {
        $this->logger->alert(
            $exception->getMessage()
            ."; File: ". $exception->getFile()
            ."; Line: ". $exception->getLine()
        );

        $forward = [
            'module'        => 'front',
            'controller'    => 'error'
        ];

        switch ($exception) {
            case $exception instanceof TranslationNotFoundException:
                $forward['action'] = 'show404';
                $forward['params'] = [
                    'lang' => $this->config->translations->defaultLang
                ];
                break;
            case $exception instanceof DispatcherException:
                switch ($exception->getCode()) {
                    case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                    case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                        $forward['action'] = 'show404';

                }
                break;
            default:
                $forward['action'] = 'show503';
                $forward['params'] = [
                    'message'   => $exception->getMessage()
                ];
        }

        $dispatcher->forward($forward);
        return false;
    }
}
