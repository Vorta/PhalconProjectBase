<?php

return new Phalcon\Config([
    'database' => [
        'adapter'       => 'Mysql',
        'host'          => 'localhost',
        'username'      => 'phalcon_project_user',
        'password'      => 'Ruc=$6&AneJ@WAze?aS6eNEprUF3#Tas',
        'dbname'        => 'phalcon_project',
        'charset'       => 'utf8mb4'
    ],
    'redis' => [
        'uniqueId'      => 'phalcon_project',
        'host'          => 'localhost',
        'port'          => '6379',
        'persistent'    => false,
        'lifetime'      => 3600,
        'prefix'        => 'project_'
    ],
    'application' => [
        'publicUrl'     => 'http'. (SSL ? 's' : '') .'://www.'. DOMAIN .'.'. TLD .'/',
        'staticUrl'     => 'http'. (SSL ? 's' : '') .'://static.'. DOMAIN .'.'. TLD .'/',
        'cryptSalt'     => 'm&YSF7!XbCpuZ@cr!J?hfXK=#$up?d?7B#ukjCj^$QqYkGzrbV#qy@x!gFA*?9T*',
        'cacheDir'      => PROJECT_ROOT .'/var/'
    ],
    'logger' => [
        'path'          => PROJECT_ROOT .'/var/log/',
        'format'        => '[%date%] %type%: %message%',
        'date'          => 'Y-m-d H:i:s',
        'logLevel'      => Phalcon\Logger::DEBUG,
        'filename'      => 'app.log',
    ],
    'translations' => [
        'defaultLang'   => 'en',
        'directory'     => PROJECT_ROOT .'/config/translations/'
    ]
]);