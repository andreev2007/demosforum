<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => $_ENV['DB_CONNECTION'] . ":host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'],
            'username' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
            'charset' => 'utf8',
          /*  'enableSchemaCache' => true,

            // Duration of schema cache.
            'schemaCacheDuration' => 3600,

            // Name of the cache component used to store schema information
            'schemaCache' => 'cache',*/
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'encryption' => 'tls',
                'host' => $_ENV['DB_HOST'],
                'port' => '5679',
                'username' => 'admin',
                'password' => 'Kavabunga243PHp  ',
            ],
        ],
    ],
];
