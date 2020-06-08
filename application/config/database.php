<?php

return [
    'default-connection' => 'concrete',
    'connections' => [
        'concrete' => [
            'driver' => 'c5_pdo_mysql',
            'server' => 'tdhy2n0x4yw8nd.cszobye4yuyb.us-east-1.rds.amazonaws.com',
            'database' => 'databasename_db',
            'username' => 'databaseuser_db',
            'password' => 'databasepw_db',
            'character_set' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
    ],
];
