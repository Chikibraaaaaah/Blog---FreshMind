<?php

use Tracy\Debugger;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\Setup;

require __DIR__ . '/../vendor/autoload.php';

Debugger::enable();

require __DIR__ . '/../bootstrap.php';

$router = new \App\Router();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

var_dump($entityManager);
die();


$router->run();
