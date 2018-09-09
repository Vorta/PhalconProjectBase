<?php

namespace Project\Front\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Translate\Adapter\NativeArray;

/**
 * Class ErrorController
 * @package Project\Front\Controllers
 * @property NativeArray t
 */
class ErrorController extends Controller
{
    /**
     * Try to give error messages in user's language
     */
    public function initialize()
    {
        $this->t = $this->di->getTranslator();
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
    public function show503Action(?string $errorMessage = 'ERR_503')
    {
        //echo $this->t->t($errorMessage);
        echo $errorMessage;
    }

    /**
     * Handles redirect from / to default language
     */
    public function languageUndefinedAction()
    {
        return $this->response->redirect($this->config->translations->defaultLang, true, 301);
    }
}
