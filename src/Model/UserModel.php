<?php

namespace App\Model;

class UserModel extends MainModel
{
    private string $userName;

    private string $email;

    private string $password;

    private \boolean $role;

    private \boolean $approuved;

    private \DateTime $createdAt;

    private \DateTime $updatedAt;
}