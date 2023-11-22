<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Mailer\EventListener\MessageListener;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Twig\Environment as TwigEnvironment;

class UserController extends MainController
{

    private $user;

    private $loggedUser;

    private $usersToApprouve = [];

    private $alert = [];

    private $destination;

    private $email; 

    private $form;


    public function defaultMethod()
    {

    }

    public function getUserMethod()
    {
        $this->user       = $this->getUserById();
        $this->alert      = $this->getSession("alert") ?? [];
        $this->loggedUser = $this->getSession("user");
        $this->usersToApprouve = new AdminController();
        $this->usersToApprouve = $this->usersToApprouve->getUnapprouvedUsers();

        return $this->twig->render("user/userProfile.twig", [
            "user"          => $this->user,
            "alert"         => $this->alert,
            "loggedUser"    => $this->loggedUser,
            "method"        => "GET",
            "waitings"      => $this->usersToApprouve
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
            $this->redirect("user_get", ["id" => $this->user["id"]]);
        }

        $fileInput              = new ServiceController();
        $this->user             = $this->getUserById();
        $this->user["imgUrl"]   = $fileInput->updatePicture();;

        ModelFactory::getModel("User")->updateData((int) $this->user["id"], $this->user);
     
        $this->setSession([
            "alert"     => "success",
            "message"   => "Votre photo de profil a bien été mise à jour."
        ]);

        $this->redirect("user_get", ["id" => $this->user["id"]]);
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

            $this->redirect("user_get", ["id" => $updatedUser["id"]]);
        }
    }

    public function getByEmail()
    {
        $this->user["email"]    = $this->getPost("email");
        $this->user             = ModelFactory::getModel("User")->readData($this->user["email"], "email",["password"]);

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
        $this->user                 = $this->getByEmail();
        $this->email["receiver"]    = $this->user["email"];
        $this->email["subject"]     = "Réinitialisez votre mot de passe";
        $this->email["content"]     = "Bonjour " . $this->user["userName"] . ",\n\nVous avez demandé de réinitialisez votre mot de passe.\nPour cela, veuillez cliquer sur le lien suivant : http://localhost:8888/Blog/BlogFinal/public/index.php?access=auth_resetPassword\nBisous carresse";

        $mail = $this->sendMailMethod($this->email["subject"], $this->email["content"], $this->email["receiver"]);
    }

    public function contactMethod()
    {
        $this->form                 = $this->getPost();
        $this->email["receiver"]    = "tristanriedinger@gmail.com";
        $this->email["subject"]     = $form["precision"];
        $this->email["content"]     = $form["demande"];

        $this->sendMailMethod($this->email["subject"], $this->email["content"], $this->email["receiver"]);
    }

    // public function sendMailMethod($subject, $content, $receiver)
    // {
    //     $this->email["receiver"]     = $receiver;
    //     $this->email["subject"]      = $subject;
    //     $this->email["content"]      = $content;        // Créer un objet de transport SMTP
    //     $this->email["transport"]    = new Swift_SmtpTransport("smtp.gmail.com", 587, "tls");
    //     $this->email["transport"]->setUsername("tristanriedinger@gmail.com");
    //     $this->email["transport"]->setPassword("xvajpmjxxczmfnxb");

    //     // Créer un objet de messagerie
    //     $mailer = new Swift_Mailer($this->email["transport"]);

    //     // Créer un objet de message
    //     $this->email = new Swift_Message($subject);
    //     $this->email->setFrom("tristanriedinger@gmail.com", "Tristan Riedinger - Admin Blog");
    //     $this->email->setTo($this->email["receiver"]);
    //     $this->email->setBody($this->email["content"]);

    //     // Envoyer le message
    //     $result = $mailer->send($this->email);

    //     // Vérifier le résultat de l"envoi
    //     if ($result) {
    //         $this->setSession([
    //             "alert"     => "success",
    //             "message"   => "Votre message a bien été envoyé."
    //         ]);
    //     } else {
    //         $this->setSession([
    //             "alert"     => "error",
    //             "message"   => "Un problème est survenu"
    //         ]);
    //     }

    //     $this->redirect("home");

    // }

    public function sendMailMethod()
    {
        $messageListener = new MessageListener(null, new BodyRenderer($this->twig));

        var_dump($messageListener);
        die();

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addSubscriber($messageListener);

        $transport = Transport::fromDsn('smtp://localhost', $eventDispatcher);
        $mailer = new Mailer($transport, null, $eventDispatcher);

        $email = (new TemplatedEmail())
            // ...
            ->htmlTemplate('emails/signup.html.twig')
            ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'username' => 'foo',
            ])
        ;
        $mailer->send($email);
    }


}
