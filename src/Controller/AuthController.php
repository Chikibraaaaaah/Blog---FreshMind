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

        $alert = $this->getSession("alert") ?? [];

        return $this->twig->render("auth/createAccount.twig", ["alert" => $alert]);

    }


    public function registerMethod()
    {

        $alert =  $this->getSession("alert") ?? [];

        return $this->twig->render("auth/register.twig", ["alert" => $alert]);

    }

    public function resetPasswordMethod()
    {

        $alert =  $this->getSession("alert") ?? [];

        return $this->twig->render("auth/resetPassword.twig", ["alert" => $alert]);

    }


    // Interactions functions

    // Signup Function, mettre photo de profil par defaut  https://bootdey.com/img/Content/avatar/avatar7.png

    public function loginMethod()
    {

        $user = $this->checkByEmail();

        if(count($user) > 0) {

            // var_dump($this->getPost("password"));

            if (password_verify($this->getPost("password"), $user["password"]) === TRUE) {
                $user["lastConnexion"] = date("Y-m-d H:i:s");
                ModelFactory::getModel("User")->updateData($user["id"], $user);
                $this->setSession($user, true);
                $this->setSession(["alert" => "success", "message" => "Connexion réussie."]);
                $this->redirect("home");
            }

           
        }

        $this->setSession(["alert" => "danger", "message" => "Mot de passe incorrect."]);

        return $this->createAccountMethod();

    }


    public function logoutMethod()
    {
        $this->destroyGlobal();
        $this->setSession(["alert" => "success", "message" => "A bientôt !"]);
        $this->redirect("home");
    }

    public function preventDeleteMethod()
    {
        $loggedUser = $this->getSession("user");

        return $this->twig->render("alert/alertDeleteAccount.twig", ["loggedUser" => $loggedUser]);
    }

    public function deleteAccountMethod()
    {
        $id = $this->getSession("user")["id"];
        ModelFactory::getModel("User")->deleteData($id);

        $this->destroyGlobal();
        $this->setSession(["alert" => "success", "message" => "A bientôt !"]);
        $this->redirect("home");
    }


    private function checkPasswordsCorrespond()
    {
        $password = $this->getPost("password");
        $secondPassword = $this->getPost("passwordMatch");

        if ($password !== $secondPassword) {
            $this->setSession(["alert" => "danger", "message" => "Les mots de passe ne correspondent pas."]);
            return false;
        }

        return true;
    }


    private function checkByUserName()
    {
        $userName = $this->getPost("userName");
        $userFound = ModelFactory::getModel("User")->listData($userName,"userName");

        if ($userFound === TRUE) {
            return $userFound[0];
        }
    }

    private function checkByEmail()
    {

        $email = $this->getPost("email");
        $userFound = ModelFactory::getModel("User")->readData($email,"email");

        return $userFound;

    }

    public function signupMethod()
{

    if ($this->checkInputs() === FALSE) {
        $alert =  $this->getSession("alert") ?? [];
        $this->redirect("auth_createAccount");
    }

    $userFound = $this->checkByEmail();
    
    if (count($userFound) > 0) {
        $this->setSession(["alert" => "danger", "message" => "Cet email est déjà utilisé."]);
        return $this->registerMethod();
    }

    $mpChek = $this->checkPasswordsCorrespond();

    if ($mpChek === FALSE) {
        $this->setSession([
            "alert" => "danger",
            "message" => "Les mots de passe ne correspondent pas."
        ]);
        return $this->createAccountMethod();
    }

    $user = $this->createUser();

    $this->setSession($user, true);
    $this->setSession(["alert" => "success", "message" => "Votre compte a bien été créé."]);
    $this->redirect("home");
}


    private function createUser()
    {

        $hashedPassword = password_hash($this->getPost("password"), PASSWORD_DEFAULT);
        $newUser = [
            "userName"  => $this->getPost("userName"),
            "email"     => $this->getPost("email"),
            "imgUrl"    => "https://bootdey.com/img/Content/avatar/avatar7.png",
            "password"  => $hashedPassword,
            "createdAt" => date("Y-m-d H:i:s")
        ];
        ModelFactory::getModel("User")->createData($newUser);
        $userCreated = ModelFactory::getModel("User")->readData($newUser["email"], "email");

        return $userCreated;

    }

}