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


    /**
     * Reset the user's password.
     *
     * @return mixed The rendered Twig template.
     */    
    public function resetPasswordMethod()
    {
        $this->alert =  $this->getAlert() ?? [];

        return $this->twig->render("auth/resetPassword.twig", ["alert" => $this->alert]);
    }



    /**
     * Prevents the delete method from being executed.
     *
     * @return mixed
     */
    public function preventDeleteMethod()
    {
        $this->loggedUser = $this->getSession("user");

        return $this->twig->render("alert/alertDeleteAccount.twig", ["loggedUser" => $this->loggedUser]);
    }


    /**                             Check methods                          */

    /**
     * Check if the passwords provided by the user match.
     *
     * @return bool Returns true if the passwords match, false otherwise.
     */
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



    /**
     * Check user by username.
     *
     * @throws Some_Exception_Class description of exception
     * @return Some_Return_Value
     */    
    private function checkByUserName()
    {
        $this->user["userName"] = $this->getPost("userName");
        $this->user             = ModelFactory::getModel("User")->readData($this->user["userName"],"userName");

        if ($this->user === TRUE) {
            return $this->user;
        }
    }


        
    /**
     * Check user by email.
     *
     * @return bool Returns true if the user is found, false otherwise.
     */
    private function checkByEmail()
    {
        $this->user["email"]    = $this->getPost("email");
        $userFound              = ModelFactory::getModel("User")->readData($this->user["email"],"email");

        return $userFound;
    }

    /**                                 Set methods                             */
    
    
    /**
     * Performs the login operation.
     *
     * @return void
     */
    public function loginMethod()
    {
        if($this->checkInputs() === FALSE) {
            $this->redirect("auth_createAccount");
        };

        $this->user = $this->checkByEmail();


        if ( gettype($this->user) === "array" && array_key_exists("id", $this->user) === TRUE) {
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
        }else{
            $this->setSession([
                "alert"     => "danger",
                "message"   => "Cet email n'est pas connu de nos services."
            ]);
            $this->redirect("auth_createAccount");
        }
        
    }


    /**
     * Logout the user and destroy the global session.
     *
     * @throws Some_Exception_Class if an error occurs during logout
     */
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


    /**
     * Signup method that handles the registration process.
     *
     * @throws Some_Exception_Class Exception thrown if there is an error in the registration process.
     */
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


    /**
     * Creates a new user.
     *
     * @return mixed The created user.
     */
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


    /**
     * Generates a new password.
     *
     * @param int $length The length of the password (default: 18)
     * @return string The generated password
     */
    public function generateNewPassWordMethod($length = 18){

        $bytes = random_bytes($length);
        $newPassword = base64_encode($bytes);

        return $newPassword;
    }



    
   

}
