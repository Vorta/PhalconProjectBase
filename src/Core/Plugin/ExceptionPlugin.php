<?php

namespace Project\Core\Plugin;

use Phalcon\Dispatcher;
use Phalcon\Events\Event;
use Phalcon\Logger\Adapter\File as Logger;
use Phalcon\Mvc\User\Plugin;

/**
 * Class ExceptionPlugin - Used to handle exceptions thrown within dispatcher
 * @package Project\Core\Plugin
 * @property Logger logger
 */
class ExceptionPlugin extends Plugin
{
    public function beforeException(Event $event, Dispatcher $dispatcher, \Exception $exception) {

        $this->logger->alert($exception->getMessage());

        $forward = [
            'module'        => 'front',
            'controller'    => 'error'
        ];

        switch ($exception->getCode()) {
            case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
            case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                $forward['action'] = 'show404';
                break;
            default:
                $forward['action'] = 'show503';
                $forward['params'] = [$exception->getMessage()];
        }

        $dispatcher->forward($forward);
        return false;
    }
}
