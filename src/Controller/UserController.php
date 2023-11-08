<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Swift_Message;
use Swift_Mailer;
use Swift_SmtpTransport;

class UserController extends MainController
{

    private $user;

    private $loggedUser;

    private $email;

    private $alert;

    private $password;

    private $destination;

    private $receiver;

    private $subject;

    private $content;

    private $transport;

    private $mailer;

    private $message;

    private $form;


    public function defaultMethod()
    {

    }

    public function getUserMethod()
    {
        $this->user       = $this->getUserById();
        $this->alert      = $this->getSession("alert") ?? [];
        $this->loggedUser = $this->getSession("user");

        return $this->twig->render("user/userProfile.twig", [
            "user"          => $this->user,
            "alert"         => $this->alert,
            "loggedUser"    => $this->loggedUser,
            "method"        => "GET"
        ]);
    }

    private function getUserById()
    {
        $this->user["id"] = $this->getGet("id");
        $this->user       = ModelFactory::getModel("User")->readData($this->user["id"], "id",["password"]);

        return $this->user;
    }

    public function updatePictureMethod()
    {

        if ($this->getFIles()["img"]["size"] == 0){
            $this->setSession([
                "alert"     => "error",
                "message"   => "Veuillez sélecitonner une image."
            ]);
            return $this->getUserMethod();
        }

        $fileInput              = new ServiceController();
        $this->destination      = $fileInput->updatePicture();
        $this->user             = $this->getUserById();
        $this->user["imgUrl"]   = $this->destination;

        ModelFactory::getModel("User")->updateData((int) $this->user["id"], $this->user);
     
        $this->setSession([
            "alert"     => "success",
            "message"   => "Votre photo de profil a bien été mise à jour."
        ]);

        return $this->getUserMethod();

    }

    public function editUserProfileMethod()
    {
        return $this->twig->render("user/userProfile.twig", [
            "user"          => $this->getUserById(),
            "alert"         => $this->getAlert() ?? [],
            "loggedUser"    => $this->getSession("user"),
            "method"        => "PUT"
        ]);
    }

    public function updateUserMethod()
    {
        $this->user = $this->getUserById();

        if ($this->checkInputs() === TRUE) {
            $updatedUser = array_merge($this->user, $this->getPost());
            ModelFactory::getModel("User")->updateData((int) $updatedUser["id"], $updatedUser);

            return $this->getUserMethod();
        }
    }

    public function getByEmail()
    {
        $this->email    = $this->getPost("email");
        $this->user     = ModelFactory::getModel("User")->readData($this->email, "email",["password"]);

        if ($this->user === FALSE) {
            $this->setSession([
                "alert"     => "error",
                "message"   => "Cet email n'est pas connu de nos services."
            ]);
        }

        return $this->user;
    }

    public function passwordForgetMethod()
    {
        $this->user     = $this->getByEmail();
        $this->email    = $this->user["email"];
        $this->receiver = $this->email;
        $this->subject  = "Réinitialisez votre mot de passe";
        $this->content  = "Bonjour " . $this->user["userName"] . ",\n\nVous avez demandé de réinitialisez votre mot de passe.\nPour cela, veuillez cliquer sur le lien suivant : http://localhost:8888/Blog/BlogFinal/public/index.php?access=auth_resetPassword\nBisous carresse";

        $mail = $this->sendMailMethod($this->subject, $this->content, $this->receiver);
    }

    public function contactMethod()
    {
        $this->form     = $this->getPost();
        $this->receiver = "tristanriedinger@gmail.com";
        $this->subject  = $form["precision"];
        $this->content  = $form["demande"];

        $this->sendMailMethod($this->subject, $this->content, $this->receiver);
    }

    public function sendMailMethod($subject, $content, $receiver)
    {
        $this->receiver     = $receiver;
        $this->subject      = $subject;
        $this->content      = $content;        // Créer un objet de transport SMTP
        $this->transport    = new Swift_SmtpTransport("smtp.gmail.com", 587, "tls");
        $this->transport->setUsername("tristanriedinger@gmail.com");
        $this->transport->setPassword("xvajpmjxxczmfnxb");

        // Créer un objet de messagerie
        $this->mailer = new Swift_Mailer($this->transport);

        // Créer un objet de message
        $this->message = new Swift_Message($subject);
        $this->message->setFrom("tristanriedinger@gmail.com", "Tristan Riedinger - Admin Blog");
        $this->message->setTo($this->receiver);
        $this->message->setBody($this->content);

        // Envoyer le message
        $result = $this->mailer->send($this->message);

        // Vérifier le résultat de l"envoi
        if ($result) {
            $this->setSession([
                "alert"     => "success",
                "message"   => "Votre message a bien été envoyé."
            ]);
        } else {
            $this->setSession([
                "alert"     => "error",
                "message"   => "Un problème est survenu"
            ]);
        }

        $this->redirect("home");

    }


}
