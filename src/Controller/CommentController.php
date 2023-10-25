<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;

class CommentController extends MainController
{

    public function defaultMethod()
    {
    }

    public function createCommentMethod()
    {

        $article = new ArticleController();
        $newComment = [
            "authorId"  => (int) $this->getSession("user")["id"],
            "articleId" => (int) $this->getGet("id"),
            "content"   => $this->getPost("content"),
            "createdAt" => date("Y-m-d H:i:s")
        ];

        ModelFactory::getModel("Comment")->createData($newComment);
        $this->setSession(["alert" => "success", "message" => "Nous nous réservons le droit à une première lecture avant de publier votre commentaire. Merci pour votre compréhension"]);
        $this->redirect("home");
    }

}