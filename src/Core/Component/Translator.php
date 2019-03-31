<?php

namespace Project\Core\Component;

use Phalcon\Translate\Adapter\NativeArray;

/**
 * Class Translator
 * @package Project\Core\Component
 */
class Translator extends NativeArray
{
    /**
     * @var array
     */
    private $routeTranslate;

    /**
     * Translator constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        parent::__construct($options);
        $this->routeTranslate = $options['routes'] ?? [];
    }

    /**
     * @param string $routeName
     * @return string
     */
    public function route(string $routeName): ?string
    {
        return $this->routeTranslate[$routeName] ?? null;
    }
}
