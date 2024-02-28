<?php

return
[
    'paths' => [
        'migrations' => 'db/Migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => 'sql11.freesqldatabase.com',
            'name' => 'sql11687103',
            'user' => 'sql11687103',
            'pass' => '226XpMS9cK',
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'development' => [
			'adapter' => 'mysql',
			'host' => 'sql11.freesqldatabase.com',
			'name' => 'sql11687103',
			'user' => 'sql11687103',
			'pass' => '226XpMS9cK',
			'port' => '3306',
			'charset' => 'utf8',
        ],
        'testing' => [
			'adapter' => 'mysql',
			'host' => 'sql11.freesqldatabase.com',
			'name' => 'sql11687103',
			'user' => 'sql11687103',
			'pass' => '226XpMS9cK',
			'port' => '3306',
			'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
