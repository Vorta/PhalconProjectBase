<?php

namespace Project\Front\Controllers;

use Phalcon\Config;
use Phalcon\DiInterface;
use Phalcon\Mvc\Controller;
use Phalcon\Http\ResponseInterface;
use Phalcon\Translate\Adapter\NativeArray;

/**
 * Class ErrorController
 * @package Project\Front\Controllers
 * @property NativeArray $t
 * @property DiInterface $di
 * @property Config $config
 * @property ResponseInterface $response
 */
class ErrorController extends Controller
{
    /**
     * @var NativeArray
     */
    protected $t;

    /**
     * Try to give error messages in user's language
     */
    public function initialize()
    {
        $this->t = $this->di->get('translator');
    }

    /**
     * Handles 404 pages gracefully
     */
    public function show404Action()
    {
        echo $this->t->t('ERR_404');
    }

    /**
     * Handles exceptions gracefully
     * @param string $errorMessage
     */
    public function show503Action(?string $errorMessage = null)
    {
        //echo $this->t->t($errorMessage);
        echo $errorMessage ?? 'ERR_503';
    }

    /**
     * Handles redirect from / to default language
     */
    public function languageUndefinedAction()
    {
        return $this->response->redirect(
            $this->config->get('translations')->defaultLang,
            true,
            301
        );
    }
}
