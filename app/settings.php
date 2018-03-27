<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        'databases' => [
            'rattrapage' => [
                'host' => getenv('DB_RATTRAPAGE_HOST'),
                'databaseName' => getenv('DB_RATTRAPAGE_DATABASE'),
                'user' => getenv('DB_RATTRAPAGE_USER'),
                'password' => getenv('DB_RATTRAPAGE_PASS')
            ]
        ],

    ],
];
