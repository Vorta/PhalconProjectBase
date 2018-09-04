<?php

namespace Project\Front\Controllers;

/**
 * Class ErrorController
 * @package Project\Front\Controllers
 */
class ErrorController extends ControllerBase
{
    /**
     * Handles 404 pages gracefully
     */
    public function show404Action () {
        echo "Error 404: You wandered too far";
    }

    /**
     * Handles exceptions gracefully
     * @param string $errorMessage
     */
    public function show503Action (?string $errorMessage = "Unknown error") {
        echo "Error 503: ". $errorMessage;
    }
}
