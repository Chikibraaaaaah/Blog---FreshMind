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
        
        $alert = $this->getSession()["alert"] ?? "";

        return $this->twig->render("auth/register.twig", ["alert" => $alert]);
    }

    public function resetPasswordMethod()
    {
        return $this->twig->render("auth/resetPassword.twig");
    }


    // Interactions functions

    public function loginMethod()
    {

        $user = $this->checkUserByEmail();

        if(count($user) > 0) {
            if (password_verify($this->getPost("password"), $user['password']) === TRUE) {
                $user["lastConnexion"] = date("Y-m-d H:i:s");
                ModelFactory::getModel("User")->updateData($user["id"], $user);
                $this->setSession($user, true);
                $this->setSession(["alert" => "success", "message" => "Connexion réussie."]);
                $this->redirect("home");
            }

            $this->setSession(["alert" => "danger", "message" => "Mot de passe incorrect."]);
            $this->redirect("auth_register");
        }

        $this->redirect("auth_createAccount");

    }

    protected function checkUserByEmail()
    {

        if($this->checkInputs()) {
            $email = $this->getPost("email");
            $user = ModelFactory::getModel("User")->listData($email, "email")[0] ?? [];

            return $user;
        }

    }


}