<?php

namespace Project;

use Phalcon\Di;
use Phalcon\Debug;
use Dotenv\Dotenv;
use RuntimeException;
use Phalcon\Application;
use Phalcon\Config\Exception;
use Fabfuel\Prophiler\Profiler;
use Phalcon\Cli\RouterInterface;
use Phalcon\Config\Adapter\Yaml;
use Project\Core\Component\CliParser;
use Phalcon\Mvc\Application as MvcApplication;
use Phalcon\Cli\Console as ConsoleApplication;

/**
 * Class Kernel
 * @package Project
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class Kernel
{
    public const MODE_FPM = 'n'; // Normal mode
    public const MODE_CLI = 'c'; // CLI mode
    public const MODE_API = 'a'; // API mode

    public const ENV_PRODUCTION = 'production';
    public const ENV_STAGING    = 'staging';
    public const ENV_DEV        = 'dev';
    public const ENV_TEST       = 'testing';

    /**
     * @var Application
     */
    private $application;

    /**
     * @var Di
     */
    private $di;

    /**
     * @var string
     */
    private $mode;

    /**
     * @var bool
     */
    private $debug;

    /**
     * Kernel constructor.
     * @param string $mode
     */
    public function __construct(
        string $mode = self::MODE_FPM
    ) {
        $this->mode = $mode;

        $this->setupEnvironment();

        if ($_SERVER['APP_ENV'] === self::ENV_DEV && $mode === self::MODE_FPM) {
            // Initialize web debugger
            (new Debug())->listen();
        }

        $this->di = new Di();
        $this->di->setShared('kernel', $this);

        Di::setDefault($this->di);

        /** @noinspection PhpIncludeInspection */
        $providers = require project_root('config/providers.php');
        $this->initializeServiceProviders($providers);

        $this->initializeApplication();
    }

    /**
     * Initializes the application which handles the requests
     */
    protected function initializeApplication()
    {
        switch ($this->mode) {
            case self::MODE_FPM:
                $this->application = new MvcApplication($this->di);
                break;
            case self::MODE_CLI:
                $arguments = CliParser::getArguments();
                /** @var RouterInterface $router */
                $router = $this->di->get('router');
                $router->setDefaultModule($arguments['module']);

                $this->application = new ConsoleApplication($this->di);
                $this->application->setArgument($arguments, false);
                break;
            case self::MODE_API:
                // @TODO - API application
                break;
            default:
                throw new RuntimeException("Unrecognized application mode");
        }

        try {
            $modules = new Yaml(project_root('config/modules.yaml'));

            $this->application->registerModules($modules->toArray());
        } catch (Exception $e) {
            // No modules, no problem
        }

        $this->application->setEventsManager($this->di->get('eventsManager'));
    }

    /**
     * Loads environment variables and sets up the application environment
     */
    protected function setupEnvironment()
    {
        Dotenv::create(project_root())->load();

        if (!in_array($_SERVER['APP_ENV'], [self::ENV_DEV, self::ENV_PRODUCTION, self::ENV_STAGING, self::ENV_TEST])) {
            throw new RuntimeException("Unrecognized application environment");
        }

        $this->debug = (bool) (
            $_SERVER['APP_DEBUG']
            ?? $_SERVER['APP_ENV'] == self::ENV_DEV
        );
    }

    /**
     * @param array $providers
     * @return Kernel
     */
    protected function initializeServiceProviders(
        array $providers
    ): self {
        foreach ($providers as $class => $supportedModes) {
            if (in_array('all', $supportedModes)
                || in_array($this->mode, $supportedModes)) {
                $this->di->register(new $class());
            }
        }

        return $this;
    }

    /**
     * Get application output.
     * @return string|null
     */
    public function getOutput(): ?string
    {
        if ($this->application instanceof MvcApplication) {
            return $this->application->handle()->getContent();
        }

        $this->application->handle();
        return null;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }
}
