<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tests")
 */
class Test
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="id")
     */
    private $id;



    /** Getters et Setters */

    /**
     * Retrieves the ID of the object.
     *
     * @return int|null The ID of the object.
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    

}

