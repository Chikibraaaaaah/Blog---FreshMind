<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

// Chemin vers les entités de l'application
$entityPath = [__DIR__ . "/src/Entity"];

// Configuration de l'entité Doctrine
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration($entityPath, $isDevMode);

// Paramètres de connexion à la base de données
$dbParams = [
    'dbname' => 'blog',
    'user' => 'root',
    'password' => 'root',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
];

// Connexion à la base de données et création du gestionnaire d'entités
$entityManager = EntityManager::create($dbParams, $config);



// return $entityManager;
