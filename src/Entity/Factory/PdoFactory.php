<?php

namespace App\Entity\Factory;

use Doctrine\DBAL\DriverManager;

class PdoFactory
{
/**
     * Stores the Connection
     * @var null
     */
    private static $pdo = null;

    /**
     * Returns the Connection if it exists or creates it before returning it
     * @return PDO|null
     */

     public static function getPDO()
    {

        if (self::$pdo === null) {
            $connectionParams = [
                'dbname' => 'blog',
                'user' => 'root',
                'password' => 'root',
                'host' => 'localhost',
                'driver' => 'pdo_mysql'
            ];
            
            self::$pdo = DriverManager::getConnection($connectionParams);
            self::$pdo->exec("SET NAMES UTF8");

        }

        return self::$pdo;
    }


}


