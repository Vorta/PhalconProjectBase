<?php

namespace Project\Core\Exception;

/**
 * Class TranslationNotFoundException
 * @package Project\Core\Exception
 */
class TranslationNotFoundException extends \Exception
{
    public function __construct(string $lang = "")
    {
        parent::__construct("Translation file ($lang) not found!");
    }
}
