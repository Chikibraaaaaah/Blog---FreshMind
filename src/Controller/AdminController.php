<?php


namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AdminController extends MainController
{

    private $commentsToValidate = [];

    private $usersToApprouve = [];

    private $mailer;

    private $session;


    /**
     * Retrieves the unapproved comments from the database.
     *
     * @return array The unapproved comments.
     */
    public function getUnapprouvedComments()
    {
        $this->commentsToValidate = ModelFactory::getModel("Comment")->listComment(0, "Comment.approuved", "ASC");

        return $this->commentsToValidate;
    }


    /**
     * Approves a comment.
     *
     * @throws Some_Exception_Class if there is an error updating the comment data
     */
    public function approuveCommentMethod()
    {
        $id     = $this->getGet("id");
        $user   = $this->getSession()["user"];

        ModelFactory::getModel("Comment")->updateData($id, ["approuved" => 1]);
        $this->redirect("user_getUser", ["id" => $user["id"]]);
    }
    

    /**
     * Retrieves the list of unapproved users.
     *
     * @return array The list of unapproved users.
     */
    public function getUnapprouvedUsers()
    {
        $this->usersToApprouve = ModelFactory::getModel("User")->listData(0, "approuved", "ASC");

        return $this->usersToApprouve;
    }


    /**
     * Approves a user.
     *
     * @throws Some_Exception_Class description of exception
     * @return Some_Return_Value
     */
    public function approuveUserMethod()
    {
        $id             = $this->getGet("id");
        $myAccount      = $this->getSession()["user"];

        ModelFactory::getModel("User")->updateData($id, ["approuved" => 1]);
        $this->setSession([
            "alert"     => "success",
            "message"   => "L'utilisateur a bien été approuvé."
        ]);

        $this->redirect("user_getUser", ["id" => $myAccount["id"]]);
    }

}
