<?php
require 'html/index.php';
return
    [
        'paths' => [
            'migrations' => '%%PHINX_CONFIG_DIR%%/phinx/migrations',
            'seeds' => '%%PHINX_CONFIG_DIR%%/phinx/seeds'
        ],
        'environments' => [
            'default_migration_table' => 'phinxlog',
            'default_environment' => 'development',
            'development' => [
                'name' => 'application.dev',
                'connection' => App\App::getInstance()->getDb()->getPDO()
            ],
            'prod' => [
                'name' => 'application.prod',
                'connection' => App\App::getInstance()->getDb()->getPDO()
            ]
        ]
    ];
