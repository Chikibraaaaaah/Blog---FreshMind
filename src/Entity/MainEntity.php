<?php

namespace App\Entity;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;


class MainEntity
{

    protected $database = null;


    protected $table = null;


    public function __construct($database)
    {
        $this->database = $database;
        $entity = explode("\\", get_class($this));
        $this->table = ucfirst(str_replace("Entity", "", array_pop($entity)));
        // $this->table = 'blog_user';

        var_dump($this->database);
        die();
    }

}