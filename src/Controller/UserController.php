<?php

namespace App\Controller;

use RuntimeException;
use App\Model\Factory\ModelFactory;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;


class UserController extends MainController
{

    private $user;

    private $unapprouvedUsers = [];

    private $unapprouvedComments = [];

    private $adminController;

    private $alert = [];

    private $destination;

    private $comments = [];

    private $articlesCommented = [];




    public function defaultMethod()
    {

    }

    public function getUserMethod()
    {


        $this->user                 = $this->getUserById();
        $this->alert                = $this->getAlert() ?? [];
        $loggedUser                 = $this->getSession()["user"] ?? [];
        $adminController            = new AdminController();
        $this->unapprouvedUsers     = $adminController->getUnapprouvedUsers();
        $this->unapprouvedComments  = $adminController->getUnapprouvedComments();
        $this->comments             = $this->getActivity()["comments"];
        $this->articlesCommented    = $this->getActivity()["articles"];

        return $this->twig->render("user/Profile.twig", [
            "user"                  => $this->user,
            "alert"                 => $this->alert,
            "loggedUser"            => $loggedUser,
            "method"                => "GET",
            "unapprouvedUsers"      => $this->unapprouvedUsers,
            "unapprouvedComments"   => $this->unapprouvedComments,
            "comments"              => count($this->comments),
            "articles"              => count($this->articlesCommented)
        ]);
    }

    public function getUserById()
    {
        $this->user["id"] = $this->getGet("id");
        $this->user       = ModelFactory::getModel("User")->readData($this->user["id"], "id",["password"]);

        return $this->user;
    }

    public function updatePictureMethod()
    {

        if (intval($this->getFiles()["img"]["size"]) > 0 ) {

            $service = new ServiceController();
            $destination = $service->uploadFile();  
            $user = $this->getSession("user");
            ModelFactory::getModel("User")->updateData($user["id"], array_merge($user, ["imgUrl" => $destination]));
    
            $this->redirect("user_getUser", ["id" => $user["id"]]);
           
        }else{
            $this->setSession([
                "alert"     => "danger",
                "message"   => "Veuillez choisir un fichier."
            ]);
            $this->redirect("user_getUser", [
                "id" => $this->getSession("user")["id"]
            ]);
        }


       
    }

    
    public function updateUserMethod()
    {
        $this->user = $this->getUserById();

        if ($this->checkInputs() === TRUE) {
            $updatedUser = array_merge($this->user, $this->getPost());
            ModelFactory::getModel("User")->updateData((int) $updatedUser["id"], $updatedUser);

            $this->redirect("user_getUser", ["id" => $updatedUser["id"]]);
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


    public function getActivity()
    {
        $comments           = ModelFactory::getModel("Comment")->listComment($this->getGet("id"), "comment.authorId");
        $uniqueArticleIds   = array_unique(array_column($comments, 'articleId'));

        return ["comments"  => $comments, "articles" => $uniqueArticleIds];
    }


}
