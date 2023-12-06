<?php

namespace App\Model\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="blog_user")
 */
class UserEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="id")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="userName")
     */
    private $userName;

    /**
     * @ORM\Column(type="string", name="email")
     */
    private $email;

    /**
     * @ORM\Column(type="string", name="password")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean", name="role")
     */
    private $role;

    /**
     * @ORM\Column(type="boolean", name="approved")
     */
    private $approved;

    /**
     * @ORM\Column(type="datetime", name="createdAt")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", name="updatedAt")
     */
    private $updatedAt;



    /** Getters et Setters */


    /**
     * Get the ID of the object.
     *
     * @return int|null The ID of the object.
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Retrieves the user name.
     *
     * @return string|null The user name.
     */
    public function getUserName(): ?string
    {
        return $this->userName;
    }


    /**
     * Sets the user name.
     *
     * @param string $userName The user name to set.
     */
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }


    /**
     * Retrieves the email associated with the current object.
     *
     * @return string|null The email associated with the current object, or null if no email is set.
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }


    /**
     * Sets the email address.
     *
     * @param string $email The email address to set.
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }


    /**
     * Retrieves the password.
     *
     * @return string|null The password.
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }


    /**
     * Set the password for the object.
     *
     * @param string $password The password to set.
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }


    /**
     * Retrieves the role of the object.
     *
     * @return bool|null The role of the object.
     */
    public function getRole(): ?bool
    {
        return $this->role;
    }


    /**
     * Sets the role of the object.
     *
     * @param bool $role The new role value.
     * @return void
     */
    public function setRole(bool $role): void
    {
        $this->role = $role;
    }


    /**
     * Checks if the object is approved.
     *
     * @return ?bool The approval status of the object.
     */
    public function isApproved(): ?bool
    {
        return $this->approved;
    }


    /**
     * Sets the approval status of the object.
     *
     * @param bool $approved The new approval status.
     * @return void
     */
    public function setApproved(bool $approved): void
    {
        $this->approved = $approved;
    }


    /**
     * Retrieves the value of the `createdAt` property.
     *
     * @return \DateTimeInterface|null The value of the `createdAt` property.
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }


    /**
     * Set the creation date of the object.
     *
     * @param \DateTimeInterface $createdAt The creation date to set.
     * @return void
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }


    /**
     * Retrieves the updated at date and time of the object.
     *
     * @return \DateTimeInterface|null The updated at date and time.
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }


    /**
     * Sets the updated at timestamp for the object.
     *
     * @param \DateTimeInterface $updatedAt The updated at timestamp to set.
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

}
