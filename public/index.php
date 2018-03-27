<?php
date_default_timezone_set('Europe/Paris');

if($_SERVER['REQUEST_METHOD'] == "OPTIONS") {
    header('Access-Control-Max-Age: 1728000');
}

require __DIR__.'/../vendor/autoload.php';

use \Slim\App;

$env = $_SERVER['APP_ENV'];
$dotenv = new Dotenv\Dotenv(__DIR__.'/../env/', $env.'.env');
$dotenv->load();

$settings = require __DIR__.'/../app/settings.php';

$app = new App($settings);

require __DIR__ . '/../app/dependencies.php';

require __DIR__ . '/../app/middleware.php';

require  __DIR__ . '/../app/routes.php';

$app->run();
