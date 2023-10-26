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

        // var_dump($article);
        // die();

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

    public function updateMethod()
    {
        $existingComment = ModelFactory::getModel("Comment")->listData($this->getGet("id"),"id")[0];

        if ($this->checkInputs() === TRUE) {
            $updatedComment = array_merge($existingComment, $this->getPost());
            $updatedComment["content"] = $this->encodeString($updatedComment["content"]);
            $updatedComment["updatedAt"] = date("Y-m-d H:i:s");

            ModelFactory::getModel("Comment")->updateData($existingComment["id"], $updatedComment);
            $this->redirect("article_renderArticle", ["id" => $updatedComment["articleId"]]);

        }
    }

    public function deleteMethod()
    {
        $id = $this->getGet("id");
        $comment = ModelFactory::getModel("Comment")->deleteData($id);

        $this->setSession(["alert" => "success", "message" => "Commentaire supprimé"]);
        $this->redirect("home");
    }

    public function confirmDeleteMethod()
    {
        $id = $this->getGet("id");
        $comment = ModelFactory::getModel("Comment")->readData($id, "id");
        $article = ModelFactory::getModel("Article")->readData($comment["articleId"], "id");
        $user = $this->getSession()["user"];

        return $this->twig->render("alert/alertDeleteComment.twig", [
            "article" => $article,
            "comment" => $comment,
            "loggedUser" => $user
        ]);
    }


}