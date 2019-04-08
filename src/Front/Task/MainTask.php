<?php

namespace Project\Front\Task;

use Phalcon\Cli\Task;
use League\CLImate\CLImate;

/**
 * Class MainTask
 * @package Project\Front\Task
 * @property CLImate $output
 */
class MainTask extends Task
{
    /**
     * front:main:main
     */
    public function mainAction()
    {
        $this->output->backgroundDarkGray("Sample CLI action");
    }

}
