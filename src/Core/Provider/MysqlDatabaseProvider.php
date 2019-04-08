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
            $di->get('logger')->info('Initializing Database...');
            $dbConf = $di->get('config')->get('database');

            $dbAdapter = new Mysql([
                 'host'     => $dbConf->host,
                 'dbname'   => $dbConf->dbname,
                 'port'     => $dbConf->port,
                 'username' => $dbConf->username,
                 'password' => $dbConf->password,
            ]);
            $dbAdapter->setEventsManager(
                $di->get('eventsManager')
            );
            return $dbAdapter;
        });
    }
}
