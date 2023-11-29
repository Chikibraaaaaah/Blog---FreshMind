<?php


namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use RuntimeException;


class AdminController extends MainController
{

    private $commentsToValidate = [];

    private $usersToApprouve = [];

    public function getUnapprouvedComments()
    {
        $this->commentsToValidate = ModelFactory::getModel("Comment")->listComment(0, "Comment.approuved", "ASC");

        return $this->commentsToValidate;
    }

    public function validateComment($comment)
    {
        return $comment["approuved"] = 1;
    }

    public function getUnapprouvedUsers()
    {
        $this->usersToApprouve = ModelFactory::getModel("User")->listData(0, "approuved", "ASC");

        return $this->usersToApprouve;
    }

    public function approuveUserMethod()
    {
        $id = $this->getGet("id");

        ModelFactory::getModel("User")->updateData($id, ["approuved" => 1]);
        $this->setSession([
            "alert"     => "success",
            "message"   => "L'utilisateur a bien été approuvé."
        ]);
        $this->redirect("home");
    }

}


