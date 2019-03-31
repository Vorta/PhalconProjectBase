<?php

use Phalcon\Di;

if (!function_exists('translate')) {
    /**
     * @param string $translateKey
     * @param array|null $placeholders
     * @return string
     */
    function translate(string $translateKey, ?array $placeholders = null): string
    {
        return Di::getDefault()
            ->get('translator')
            ->t($translateKey, $placeholders);
    }
}
