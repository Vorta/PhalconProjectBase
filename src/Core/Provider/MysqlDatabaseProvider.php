<?php

namespace Project\Core\Provider;

use Phalcon\DiInterface;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Di\ServiceProviderInterface;

/**
 * Class MysqlDatabaseProvider
 * @package Project\Core\Provider
 */
class MysqlDatabaseProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $di
     */
    public function register(DiInterface $di)
    {
        $di->setShared('db', function () use ($di) {
            $config = $di->get('config');
            $dbAdapter = new Mysql(
                $config->database->toArray()
            );
            $dbAdapter->setEventsManager(
                $di->get('eventsManager')
            );
            return $dbAdapter;
        });
    }
}
