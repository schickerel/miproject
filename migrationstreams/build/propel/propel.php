<?php

return [
    'propel' => [
        'database' => [
            'connections' => [
                'migrationstreams' => [
                    'adapter'    => 'mysql',
                    'classname'  => 'Propel\Runtime\Connection\ConnectionWrapper',
                    'dsn'        => 'mysql:host=localhost;dbname=migrationstreams',
                    'user'       => 'root',
                    'password'   => '',
                    'attributes' => [],
                    'settings'   => [
                        'charset'    => 'utf8',
                        'queries'    => [
                            'utf8'      => 'SET NAMES utf8 COLLATE utf8_general_ci, COLLATION_CONNECTION = utf8_general_ci, COLLATION_DATABASE = utf8_general_ci, COLLATION_SERVER = utf8_general_ci'
                        ]
                    ]
                ]
            ]
        ],
        'runtime' => [
            'defaultConnection' => 'migrationstreams',
            'connections' => ['migrationstreams']
        ],
        'generator' => [
            'defaultConnection' => 'migrationstreams',
            'connections' => ['migrationstreams']
        ]
    ]
];

?>