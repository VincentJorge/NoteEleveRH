<?php
use Upsell\DbHelper\SqlServer\DBALHelper;
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

//---------------------------------------------------------------------------
// Databases
// --------------------------------------------------------------------------
$container[App\Constants::DB_RATTRAPAGE] = function ($c) {
    $settings = $c->get('settings');
    
    $host = $settings['databases']['rattrapage']['host'];
    $databaseName = $settings['databases']['rattrapage']['databaseName'];
    $user = $settings['databases']['rattrapage']['user'];
    $password = $settings['databases']['rattrapage']['password'];
    
    $db = DBALHelper::createConnection($databaseName, $user, $password, $host);
    return $db;
};
