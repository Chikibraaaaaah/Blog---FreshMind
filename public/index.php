<?php

use Tracy\Debugger;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;



require __DIR__ . '/../vendor/autoload.php';

Debugger::enable();

$router = new \App\Router();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$router->run();
