<?php

use Project\Kernel;

define('APP_START_TIME', microtime(true));
define('APP_START_MEMORY', memory_get_usage());

require_once '../vendor/autoload.php';

$kernel = new Kernel(Kernel::MODE_FPM);
echo $kernel->getOutput();
