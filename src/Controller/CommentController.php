<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;

class CommentController extends MainController
{

    public function defaultMethod()
    {
    }

    public function createMethod()
    {

        $newComment = [
            "authorId"  => (int) $this->getSession("user")["id"],
            "articleId" => (int) $this->getGet("id"),
            "content"   => $this->getPost("content"),
            "createdAt" => date("Y-m-d H:i:s")
        ];
        // echo "<pre>"; 
        // var_dump($this->getServer("HTTP_REFERER"));
        // echo "</pre>";
        // die();

        ModelFactory::getModel("Comment")->createData($newComment);
        $this->setSession(["alert" => "success", "message" => "Nous nous réservons le droit à une première lecture avant de publier votre commentaire. Merci pour votre compréhension"]);
        
        $this->redirect("article_get", ["id" => $this->getGet("id")]);
    }

    public function getRelatedArticle()
    {
        $id = $this->getGet("id");
        $comment = ModelFactory::getModel("Comment")->readData($id, "articleId");
        $article = ModelFactory::getModel("Article")->readData($comment["articleId"], "id");
        $relatedComments = ModelFactory::getModel("Comment")->listData($article["id"], "articleId");

        return $this->twig->render("article/articleDetail.twig", [
            "article" => $article,
            "loggedUser" => $this->getSession()["user"],
            "relatedComments" => $relatedComments
        ]);
    }



    public function modifyMethod()
    {
        $comment = ModelFactory::getModel("Comment")->readData($this->getGet("id"), "id");
        $article = ModelFactory::getModel("Article")->readData($comment["articleId"], "id");
        $relatedComments = ModelFactory::getModel("Comment")->listData($article["id"], "articleId");

        return $this->twig->render("comment/commentDetail.twig", [
            "article"           => $article,
            "comment"           => $comment,
            "relatedComments"   => $relatedComments,
            "loggedUser"              => $this->getSession()["user"],

        ]);
    }




}