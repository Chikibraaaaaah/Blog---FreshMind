<?php

namespace App\Model\Entity;

class UserEntity
{

    private int $id;

    private string $userName;

    private string $email;

    private string $password;

    private bool $role;

    private bool $approved;

    private DateTime $createdAt;

    private DateTime $updatedAt;


    /**
     * Retrieves the ID of the object.
     *
     * @return int The ID of the object.
     */
    public function getId(): int {
        return $this->id;
    }


    /**
     * Sets the ID of the object.
     *
     * @param int $id The ID to set.
     * @throws \InvalidArgumentException If the ID is not an integer.
     * @return void
     */
    public function setId(int $id): void {
        $this->id = $id;
    }


    /**
     * Retrieves the user name.
     *
     * @return string The user name.
     */    
    public function getUserName(): string
    {
        return $this->userName;
    }


    /**
     * Set the user name.
     *
     * @param string $userName The new user name.
     * @throws \Exception If an error occurs.
     * @return void
     */
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }


    /**
     * Retrieves the email of the object.
     *
     * @return string The email of the object.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }


    /**
     * Retrieves the password.
     *
     * @return string The password.
     */
    public function getPassword(): string
    {
        return $this->password;
    }


    /**
     * Sets the password for the object.
     *
     * @param string $password The password to set.
     * @throws Exception If an error occurs while setting the password.
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }


    /**
     * Retrieves the role of the object.
     *
     * @return bool The role of the object.
     */
    public function getRole(): bool
    {
        return $this->role;
    }


    /**
     * Sets the role of the object.
     *
     * @param bool $role The role to be set.
     * @throws Some_Exception_Class Description of exception.
     * @return void
     */
    public function setRole(bool $role): void
    {
        $this->role = $role;
    }


    /**
     * Returns whether or not the entity is approved.
     *
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->approved;
    }


    /**
     * Sets the approval status of the object.
     *
     * @param bool $approved The new approval status.
     */
    public function setApproved(bool $approved): void
    {
        $this->approved = $approved;
    }


    /**
     * Get the creation date of the object.
     *
     * @return DateTime The creation date.
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }


    /**
     * Sets the value of the $createdAt property.
     *
     * @param DateTime $createdAt The new value for the $createdAt property.
     * @return void
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }


    /**
     * Retrieves the updated at date and time.
     *
     * @return DateTime The updated at date and time.
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }


    /**
     * Sets the updated date and time for the object.
     *
     * @param DateTime $updatedAt The updated date and time.
     */
    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

}
