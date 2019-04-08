<?php

namespace Project\CLI\Task;

use SplFileInfo;
use Phalcon\Config;
use Phalcon\Cli\Task;
use League\CLImate\CLImate;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Phalcon\Mvc\Model\MetaData\Redis as MetaDataAdapter;

/**
 * Class CacheTask
 * @package Project\CLI\Task
 * @property Config $config
 * @property CLImate $output
 * @property MetaDataAdapter $modelsMetadata
 */
class CacheTask extends Task
{
    /**
     * bin/console cache:clear
     */
    public function clearAction()
    {
        $this->output->lightGreen("Removing files...");
        $this->clearCache();

        $this->output->lightGreen(PHP_EOL ."Resetting models metadata...");
        $this->modelsMetadata->reset();
    }

    /**
     * @return bool
     */
    private function clearCache(): bool
    {
        $cacheDir = project_root($this->config->path('application.cacheDir'));

        if (file_exists($cacheDir) === false) {
            return false;
        }

        $cacheIterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($cacheDir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        /**
         * @var SplFileInfo $fileInfo
         */
        foreach ($cacheIterator as $fileInfo) {
            $this->output->darkGray('Removing '. $fileInfo->getFilename());
            if ($fileInfo->isDir()) {
                if (false === $fileInfo->getRealPath() || false === rmdir($fileInfo->getRealPath())) {
                    return false;
                }
                continue;
            }

            if (false === $fileInfo->getRealPath() || false === unlink($fileInfo->getRealPath())) {
                return false;
            }
        }

        return true;
    }
}
