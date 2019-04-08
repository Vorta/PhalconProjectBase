<?php

use Project\Kernel;
use Project\Core\Provider;

return [
    Provider\ConfigProvider::class => ['all'],
    Provider\LoggerProvider::class => ['all'],
    Provider\EventsManagerProvider::class => ['all'],
    Provider\DispatcherProvider::class => ['all'],
    Provider\UrlProvider::class => [Kernel::MODE_FPM],
    Provider\RouterProvider::class => [Kernel::MODE_FPM],
    Provider\CliRouterProvider::class => [Kernel::MODE_CLI],
    Provider\CliMateProvider::class => [Kernel::MODE_CLI],
    Provider\VoltProvider::class => ['all'],
    Provider\ViewProvider::class => ['all'],
    Provider\MysqlDatabaseProvider::class => ['all'],
    Provider\ModelsManagerProvider::class => ['all'],
    Provider\ModelsMetadataProvider::class => ['all'],
    Provider\SessionProvider::class => [Kernel::MODE_FPM],
    Provider\SessionBagProvider::class => [Kernel::MODE_FPM],
    Provider\FlashProvider::class => [Kernel::MODE_FPM],
    Provider\CryptProvider::class => ['all'],
    Provider\SecurityProvider::class => ['all'],
    Provider\AuthProvider::class => [Kernel::MODE_FPM],
    Provider\AclResourcesProvider::class => [Kernel::MODE_FPM],
    Provider\AclProvider::class => [Kernel::MODE_FPM],
    Provider\TranslatorProvider::class => [Kernel::MODE_FPM],
    Provider\AssetsProvider::class => [Kernel::MODE_FPM],
    Provider\TagProvider::class => [Kernel::MODE_FPM],
    Provider\EscaperProvider::class => ['all'],
    Provider\RequestProvider::class => [Kernel::MODE_FPM],
    Provider\ResponseProvider::class => [Kernel::MODE_FPM]
];