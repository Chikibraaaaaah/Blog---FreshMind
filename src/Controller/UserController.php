<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends MainController
{

    private $user;

    private $loggedUser;

    private $unapprouvedUsers = [];

    private $unapprouvedComments = [];

    private $adminController;

    private $alert = [];

    private $destination;

    private $email; 

    private $form;

    private $comments = [];

    private $articlesCommented = [];

    private $dotenv;


    public function defaultMethod()
    {

    }

    public function getUserMethod()
    {
        $this->user                 = $this->getUserById();
        $this->alert                = $this->getSession("alert") ?? [];
        $this->loggedUser           = $this->getSession("user");
        $this->adminController      = new AdminController();
        $this->unapprouvedUsers     = $this->adminController->getUnapprouvedUsers();
        $this->unapprouvedComments  = $this->adminController->getUnapprouvedComments();
        $this->comments             = $this->getActivity()["comments"];
        $this->articlesCommented    = $this->getActivity()["articles"];

        return $this->twig->render("user/userProfile.twig", [
            "user"                  => $this->user,
            "alert"                 => $this->alert,
            "loggedUser"            => $this->loggedUser,
            "method"                => "GET",
            "unapprouvedUsers"      => $this->unapprouvedUsers,
            "unapprouvedComments"   => $this->unapprouvedComments,
            "comments"              => count($this->comments),
            "articles"              => count($this->articlesCommented)
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
        // $this->form                 = $this->getPost();
        // $this->email["receiver"]    = "tristanriedinger@gmail.com";
        // $this->email["subject"]     = $this->form["precision"];
        // $this->email["content"]     = $this->form["demande"];

        // $this->sendMailMethod();


        
    }

    public function sendEmailMethod(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('alexisbateaux@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);
      
    }



    public function getActivity()
    {
        $comments           = ModelFactory::getModel("Comment")->listComment($this->getGet("id"), "comment.authorId");
        $uniqueArticleIds   = array_unique(array_column($comments, 'articleId'));
        return ["comments"  => $comments, "articles" => $uniqueArticleIds];
    }


}
