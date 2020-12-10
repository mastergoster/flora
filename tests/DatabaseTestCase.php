<?php

namespace Tests;

use PDO;
use Phinx\Config\Config;
use Phinx\Migration\Manager;
use Core\Controller\Database\DatabaseController;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Core\Controller\Database\DatabaseMysqliteController;

class DatabaseTestCase extends DatabaseMysqliteController
{

    public function getPDO()
    {
        if (is_null($this->pdo)) {
            $this->pdo = new PDO(
                'sqlite::memory:',
                null,
                null,
                [
                    PDO::ATTR_PERSISTENT => false,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
                ]
            );
        }

        return $this->pdo;
    }

    public function getManager()
    {

        $configArray = [
            'paths' => [
                'migrations' => dirname(__DIR__) . '/phinx/migrations',
                'seeds' => __DIR__ . '/seedsTest'
            ],
            'environments' => [
                'default_migration_table' => 'phinxlog',
                'default_environment' => 'test',
                'test' => [
                    'adapter'    => 'sqlite',
                    'connection' => $this->getPDO()
                ]
            ]
        ];
        $config = new Config($configArray);
        return new Manager($config, new StringInput(' '), new NullOutput());
    }

    public function migrateDatabase()
    {

        $this->getPDO()->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_BOTH);
        $this->getManager()->migrate('test');
        $this->getPDO()->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }

    public function seedDatabase()
    {
        $this->getPDO()->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_BOTH);
        $this->getManager()->migrate('test');
        $this->getManager()->seed('test');
        $this->getPDO()->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }
}
