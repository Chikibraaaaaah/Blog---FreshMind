
<?php

require "vendor/autoload.php";

use Doctrine\DBAL\DriverManager;

$connectionParams = [
    'driver'   => 'mysqli',
    'host'     => 'localhost',
    'dbname'   => 'blog',
    'user'     => 'root',
    'password' => '',
    'charset'  => 'utf8',
    'port'     => 3306
];

$test = [
    "url" => "mysql://root:root@127.0.0.1:3306/blog?serverVersion=5.7"
];


$conn = DriverManager::getConnection($connectionParams);
$stmt = $conn->prepare("SELECT * FROM Article");

var_dump($stmt);
die();

// $result = $stmt->