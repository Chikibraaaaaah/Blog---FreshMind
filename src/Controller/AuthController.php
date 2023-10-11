<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;

class AuthController extends MainController
{

    protected $email; 

    protected $password;

    public function defaultMethod()
    {
        
    }

    // Render Functions

    public function createAccountMethod()
    {
        return $this->twig->render("auth/createAccount.twig");
    }

    public function registerMethod()
    {
        return $this->twig->render("auth/register.twig");
    }

    public function resetPasswordMethod()
    {
        return $this->twig->render("auth/resetPassword.twig");
    }


    // Interactions functions

    public function loginMethod()
    {

        $user = $this->checkUserByEmail();
        var_dump($user);
        die();


    }

    protected function checkUserByEmail()
    {
        
        $email = $this->getPost("email");
        $user = ModelFactory::getModel("User")->listData($email, "email")[0];

        if ($user) {
            return $user;
        }

        $this->setSession("alert", [
            "type" => "danger",
            "message" => "Cet email est inconnu de nos services."
        ]);

        return false;
    }

  
}