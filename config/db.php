<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => sprintf(
        'sqlsrv:Server=%s,%d;Database=%s;TrustServerCertificate=1;Encrypt=1',
        getenv('MSSQL_HOST'),
        getenv('MSSQL_PORT'),
        getenv('MSSQL_DATABASE')
    ),
    'username' => getenv('MSSQL_USER'),
    'password' => getenv('MSSQL_SA_PASSWORD'),
    'charset' => 'utf8',
    'driverName' => 'mssql',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
