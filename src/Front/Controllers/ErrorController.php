<?php

namespace Project\Front\Controllers;

use Phalcon\Config;
use Phalcon\Mvc\Controller;
use Phalcon\Http\ResponseInterface;

/**
 * Class ErrorController
 * @package Project\Front\Controllers
 * @property Config $config
 * @property ResponseInterface $response
 */
class ErrorController extends Controller
{
    /**
     * Handles 400 pages gracefully
     */
    public function show400Action()
    {
        echo translate('ERR_400');
    }

    /**
     * Handles 404 pages gracefully
     */
    public function show404Action()
    {
        echo translate('ERR_404');
    }

    /**
     * Handles exceptions gracefully
     */
    public function show500Action()
    {
        echo translate('ERR_500');
    }

    /**
     * Handles exceptions gracefully
     */
    public function show503Action()
    {
        echo translate('ERR_503');
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
