<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport\Dsn;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AuthController extends MainController
{


    private $alert;

    private $user;

    private $loggedUser;

    private $secondPassword;

    private $mailer;


    /**                                 Render methods                             */

    /**
     * A description of the entire PHP function.
     *
     * @throws Some_Exception_Class description of exception
     * @return Some_Return_Value
     */
    public function defaultMethod()
    {
        $this->alert = $this->getAlert() ?? [];
        
        $this->redirect("auth_register", ["alert" => $this->alert]);
    }


    /**
     * Creates an account method.
     *
     * @return string The rendered view of the "auth/createAccount.twig" template with the alert variable.
     */
    public function createAccountMethod()
    {
        $this->alert = $this->getAlert(true) ?? [];

        return $this->twig->render("auth/createAccount.twig", ["alert" => $this->alert]);
    }


    /**
     * Registers a method.
     *
     * @return string The rendered template.
     */
    public function registerMethod()
    {
        $this->alert = $this->getAlert(true) ?? [];

        return $this->twig->render("auth/register.twig", ["alert" => $this->alert]);
    }

    public function resetPasswordMethod()
    {
        $this->alert =  $this->getAlert() ?? [];

        return $this->twig->render("auth/resetPassword.twig", ["alert" => $this->alert]);
    }

    public function preventDeleteMethod()
    {
        $this->loggedUser = $this->getSession("user");

        return $this->twig->render("alert/alertDeleteAccount.twig", ["loggedUser" => $this->loggedUser]);
    }


    /**                             Check methods                          */

    private function checkPasswordsCorrespond()
    {
        $this->user["password"] = $this->getPost("password");
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
        $this->user             = ModelFactory::getModel("User")->readData($this->user["userName"],"userName");

        if ($this->user === TRUE) {
            return $this->user;
        }
    }

    private function checkByEmail()
    {
        $this->user["email"]    = $this->getPost("email");
        $userFound              = ModelFactory::getModel("User")->readData($this->user["email"],"email");

        return $userFound;
    }

    /**                                 Set methods                             */

    public function loginMethod()
    {
        if($this->checkInputs() === FALSE) {
            $this->redirect("auth_createAccount");
        };

        $this->user = $this->checkByEmail();

        if( array_key_exists("id", $this->user) === TRUE) {
            if (password_verify($this->getPost("password"), $this->user["password"]) === TRUE) {
                $this->user["updatedAt"] = date("Y-m-d H:i:s");
                ModelFactory::getModel("User")->updateData($this->user["id"], $this->user);
                $this->setSession($this->user, true);
                $this->setSession([
                    "alert"     => "success",
                    "message"   => "Connexion réussie."
                ]);
                $this->redirect("home");
            }else{
                $this->setSession([
                    "alert"     => "danger",
                    "message"   => "Mot de passe incorrect."
                ]);
                $this->redirect("auth_register");
            }
        }
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


    /**
     * Deletes the user account.
     *
     * @throws Some_Exception_Class In case of an error during deletion.
     */
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


    public function signupMethod()
    {
        if ($this->checkInputs() === FALSE) {
            $this->redirect("auth_createAccount");
        }

        $userFound = $this->checkByEmail();

        if ($userFound) {
            $this->setSession([
                "alert"     => "danger",
                "message"   => "Cet email est déjà utilisé."
            ]);
            $this->redirect("auth_register");
        }

        $mpChek = $this->checkPasswordsCorrespond();

        if ($mpChek === FALSE) {
            $this->redirect("auth_createAccount");
        }
        
        $this->user = $this->createUser();

        $this->setSession($this->user, true);
        $this->setSession([
            "alert" => "success",
            "message" => "Votre compte a bien été créé."
        ]);
        $this->redirect("home");
    }

    public function createUser()
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

    public function generateNewPassWordMethod($length = 18){

        $bytes = random_bytes($length);
        $newPassword = base64_encode($bytes);

        return $newPassword;
    }



    
   

}
