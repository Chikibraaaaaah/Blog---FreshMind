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
    'driver'   => 'pdo_mysql',
    'host'     => 'localhost',
    'charset'  => 'utf8',
    'user'     => 'root',
    'password' => 'root',
    'dbname'   => 'blog',
];

// Connexion à la base de données et création du gestionnaire d'entités
$entityManager = EntityManager::create($dbParams, $config);
$connection = $entityManager->getConnection();

// if ($connection->isConnected()) {
//     echo "Connecté à la base de données.";
// } else {
//     echo "Non connecté à la base de données.";
// }

echo "<pre>";
var_dump($connection);
echo "</pre>";

die();

return $entityManager;
