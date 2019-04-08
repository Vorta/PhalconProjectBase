<?php

namespace Project\Core\Component;

use InvalidArgumentException;

/**
 * Class CliParser
 * @package Project\Core\Component
 */
class CliParser
{
    /**
     * @return array
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public static function getArguments()
    {
        $argv = $_SERVER['argv'];
        $command = $argv[1] ?? null;
        $argc = count($argv);

        if (is_null($command)) {
            throw new InvalidArgumentException("Command is missing");
        }

        $command = explode(':', $command);

        switch (count($command)) {
            case 3: // Standard command
                $module = $command[0];
                $task   = $command[1];
                $action = $command[2];
                break;
            case 2: // Special command
                $module = 'cli';
                $task   = $command[0];
                $action = $command[1];
                break;
            default:
                throw new InvalidArgumentException("Unrecognized command");
        }

        $params = [];

        for ($i = 2; $i < $argc; $i++) {
            $a = $argv[$i];

            if (strlen($a) == 2 && substr($a, 0, 1) === '-') {
                $key = substr($a, 1, 1);
                if (preg_match('/^[a-z]$/i', $key) === 0) {
                    throw new InvalidArgumentException("Incorrectly formatted command");
                }
                $value = $argv[$i+1] ?? null;
                $params[$key] = $value;
                if (!is_null($value)) {
                    $i++;
                }
                continue;
            } elseif (substr($a, 0, 2) === '--') {
                $a = ltrim($a, '-');
                $mid = strpos($a, '=');
                $value = null;
                $key = $a;
                if ($mid !== false) {
                    $key = substr($a, 0, $mid);
                    $value = substr($a, $mid +1);
                }
                $params[$key] = $value;
                continue;
            }

            throw new InvalidArgumentException("Incorrectly formatted command");
        }

        return compact('module', 'task', 'action', 'params');
    }
}
