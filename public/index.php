<?php

use Tracy\Debugger;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\Setup;

require __DIR__ . '/../vendor/autoload.php';

Debugger::enable();

require __DIR__ . '/../bootstrap.php';

$router = new \App\Router();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$router->run();
