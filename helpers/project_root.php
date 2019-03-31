<?php

if (!function_exists('project_root')) {
    /**
     * @param string $path
     * @return string
     */
    function project_root(string $path = ''): string
    {
        return realpath(dirname(__DIR__)). DIRECTORY_SEPARATOR . $path;
    }
}
