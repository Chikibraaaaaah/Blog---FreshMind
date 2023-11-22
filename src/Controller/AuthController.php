<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;

class AuthController extends MainController
{


    private $alert;

    private $user;

    private $loggedUser;

    private $secondPassword;


    /**
     * A description of the entire PHP function.
     *
     * @throws Some_Exception_Class description of exception
     * @return Some_Return_Value
     */
    public function defaultMethod()
    {
        $this->redirect("auth_register");
    }


    /**
     * Creates an account method.
     *
     * @return string The rendered view of the "auth/createAccount.twig" template with the alert variable.
     */
    public function createAccountMethod()
    {
        $this->alert = $this->getAlert() ?? [];

        return $this->twig->render("auth/createAccount.twig", ["alert" => $this->alert]);
    }


    /**
     * Registers a method.
     *
     * @return string The rendered template.
     */
    public function registerMethod()
    {
        $this->alert = $this->getAlert() ?? [];

        return $this->twig->render("auth/register.twig", ["alert" => $this->alert]);
    }

    public function resetPasswordMethod()
    {
        $this->alert =  $this->getAlert() ?? [];

        return $this->twig->render("auth/resetPassword.twig", ["alert" => $this->alert]);
    }

    // Interactions functions

    // Signup Function, mettre photo de profil par defaut  https://bootdey.com/img/Content/avatar/avatar7.png

    public function loginMethod()
    {
        $this->user = $this->checkByEmail();

        if(count($this->user) > 0) {
            if (password_verify($this->getPost("password"), $this->user["password"]) === TRUE) {
                $this->user["updatedAt"] = date("Y-m-d H:i:s");
                ModelFactory::getModel("User")->updateData($this->user["id"], $this->user);
                $this->setSession($this->user, true);
                $this->setSession([
                    "alert"     => "success",
                    "message"   => "Connexion réussie."
                ]);
                $this->redirect("home");
            }
        }
        $this->setSession([
            "alert"     => "danger",
            "message"   => "Cet email n'est pas connu de nos services."
        ]);

        $this->redirect("auth_createAccount");

    }

    public function logoutMethod()
    {
        $this->destroyGlobal();
        $this->setSession([
            "alert"     => "success",
            "message"   => "A bientôt !"]
        );
        $this->redirect("home");
    }

    public function preventDeleteMethod()
    {
        $this->loggedUser = $this->getSession("user");

        return $this->twig->render("alert/alertDeleteAccount.twig", ["loggedUser" => $this->loggedUser]);
    }

    public function deleteAccountMethod()
    {
        $id = $this->getSession("user")["id"];

        ModelFactory::getModel("User")->deleteData($id);
        $this->destroyGlobal();
        $this->setSession([
            "alert"     => "success",
            "message"   => "A bientôt !"
        ]);
        $this->redirect("home");
    }


    private function checkPasswordsCorrespond()
    {
        $this->user["password"]         = $this->getPost("password");
        $this->secondPassword   = $this->getPost("passwordMatch");

        if ($this->user["password"] !== $this->secondPassword) {
            $this->setSession([
                "alert"     => "danger",
                "message"   => "Les mots de passe ne correspondent pas."]
            );
            return false;
        }

        return true;
    }


    private function checkByUserName()
    {
        $this->user["userName"] = $this->getPost("userName");
        $this->user     = ModelFactory::getModel("User")->readData($this->user["userName"],"userName");

        if ($this->user === TRUE) {
            return $this->user;
        }
    }

    private function checkByEmail()
    {

        $this->user["email"]    = $this->getPost("email");
        $userFound      = ModelFactory::getModel("User")->readData($this->user["email"],"email");

        return $userFound;

    }

    public function signupMethod()
{

    if ($this->checkInputs() === FALSE) {
        $this->alert =  $this->getAlert ?? [];
        $this->redirect("auth_createAccount");
    }

    $userFound = $this->checkByEmail();

    // var_dump($userFound);
    // die();

    if ($userFound) {
        $this->setSession([
            "alert"     => "danger",
            "message"   => "Cet email est déjà utilisé."
        ]);

        $this->redirect("auth_register");
    }

    $mpChek = $this->checkPasswordsCorrespond();

    if ($mpChek === FALSE) {
        $this->setSession([
            "alert"     => "danger",
            "message"   => "Les mots de passe ne correspondent pas."
        ]);
        $this->redirect("auth_createAccount");
    }

    $user = $this->createUser();

    $this->setSession($user, true);
    $this->setSession([
        "alert" => "success",
        "message" => "Votre compte a bien été créé."
    ]);
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