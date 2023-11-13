<?php


namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use RuntimeException;


class AdminController extends MainController
{

    private $commentsToValidate = [];

    private $usersToApprouve = [];

    public function getUnvalidComments()
    {
        $this->commentsToValidate = ModelFactory::getModel("Comment")->listData(0, "approuved", "ASC");

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

    public function approuveUserMethod($user)
    {
        return $user["approuved"] = 1;
    }

}


