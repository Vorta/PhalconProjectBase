<?php

namespace Project\Core\Component;

/**
 * Volt Extension, can implement event methods:
 * compileFunction, compileFilter, resolveExpression, compileStatement
 * Class VoltFunctionsExtension
 * @package Project\Core\Component
 */
class VoltFunctionsExtension
{
    /**
     * Triggered before trying to compile any function call in a template
     * @link https://docs.phalconphp.com/3.4/en/volt.html#extensions
     * @param string $name
     * @param string $resolvedArgs
     * @return null|string
     */
    public function compileFunction(string $name, string $resolvedArgs): ?string
    {
        switch ($name) {
            // Translation shortcut
            case 't':
                return '$this->translator->t('. $resolvedArgs .')';
        }

        return null;
    }
}
