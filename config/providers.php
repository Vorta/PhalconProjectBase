<?php

return [
    \Project\Core\Provider\ConfigProvider::class,
    \Project\Core\Provider\EventsManagerProvider::class,
    \Project\Core\Provider\MvcDispatcherProvider::class,
    \Project\Core\Provider\UrlProvider::class,
    \Project\Core\Provider\RouterProvider::class,
    \Project\Core\Provider\ViewProvider::class,
    \Project\Core\Provider\MysqlDatabaseProvider::class,
    \Project\Core\Provider\ModelsManagerProvider::class,
    \Project\Core\Provider\ModelsMetadataProvider::class,
    \Project\Core\Provider\SessionProvider::class,
    \Project\Core\Provider\SessionBagProvider::class,
    \Project\Core\Provider\FlashProvider::class,
    \Project\Core\Provider\CryptProvider::class,
    \Project\Core\Provider\SecurityProvider::class,
    \Project\Core\Provider\AuthProvider::class,
    \Project\Core\Provider\AclResourcesProvider::class,
    \Project\Core\Provider\AclProvider::class,
    \Project\Core\Provider\LoggerProvider::class,
    \Project\Core\Provider\TranslatorProvider::class,
    \Project\Core\Provider\AssetsProvider::class,
    \Project\Core\Provider\TagProvider::class,
    \Project\Core\Provider\EscaperProvider::class,
    \Project\Core\Provider\RequestProvider::class,
    \Project\Core\Provider\ResponseProvider::class
];