<?php

namespace App\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class ManagerEntity
 * @package App\Entity
 */
class ManagerEntity
{
    /**
     * @var EntityManagerInterface
     */
    private $em;




    public function __construct(EntityManagerInterface $em)
    {
        // $this->entityManager = $entityManager;

        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
        $classes = array(
          $em->getClassMetadata('Entities\User'),
          $em->getClassMetadata('Entities\Profile')
        );
        $tool->createSchema($classes);
        $this->em = $em;

        var_dump($tool);
        die();
    }







}