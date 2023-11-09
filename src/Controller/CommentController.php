<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;

class CommentController extends MainController
{

    private $alert;

    private $id;

    private $comment;

    private $comments = [];

    private $article;

    private $loggedUser;

    private $relatedComments;

    public function defaultMethod()
    {

    }

    public function createMethod()
    {

        $this->comment = [
            "authorId"  => (int) $this->getSession("user")["id"],
            "articleId" => (int) $this->getGet("id"),
            "content"   => $this->getPost("content"),
            "createdAt" => date("Y-m-d H:i:s")
        ];

        ModelFactory::getModel("Comment")->createData($this->comment);
        $this->setSession([
            "alert"     => "success",
            "message"   => "Nous nous réservons le droit à une première lecture avant de publier votre commentaire. Merci pour votre compréhension"
        ]);
        $this->redirect("article_get", ["id" => $this->comment["articleId"]]);
    }

    public function getRelatedArticle($id)
    {
        $this->article            = ModelFactory::getModel("Article")->readData($id, "id");
        $this->relatedComments    = ModelFactory::getModel("Comment")->listData($this->article["id"], "articleId");
        $this->alert              = $this->getAlert() ?? [];

        return $this->twig->render("article/getOneArticle.twig", [
            "article"           => $this->article,
            "loggedUser"        => $this->getSession()["user"],
            "relatedComments"   => $this->relatedComments,
            "alert"             => $this->alert
        ]);
    }


    public function modifyMethod()
    {
        // Récuperer comment dans les comments
        $this->comment            = ModelFactory::getModel("Comment")->readData($this->getGet("id"), "id");
        $this->article            = ModelFactory::getModel("Article")->readData($this->comment["articleId"], "id");
        $this->relatedComments    = ModelFactory::getModel("Comment")->listData($this->article["id"], "articleId");

        return $this->twig->render("comment/getComment.twig", [
            "article"           => $this->article,
            "comment"           => $this->comment,
            "relatedComments"   => $this->relatedComments,
            "loggedUser"        => $this->getSession()["user"]
        ]);
    }

    public function updateMethod()
    {
        $this->comment = ModelFactory::getModel("Comment")->readData($this->getGet("id"),"id");

        if ($this->checkInputs() === TRUE) {
            $updatedComment = array_merge($this->comment, $this->getPost());
            $updatedComment["content"] = $this->encodeString($updatedComment["content"]);
            $updatedComment["updatedAt"] = date("Y-m-d H:i:s");

            ModelFactory::getModel("Comment")->updateData($this->comment["id"], $updatedComment);
            $this->setSession([
                "alert"     => "success",
                "message"   => "Votre commentaire a été mis à jour."
            ]);
            $this->redirect("article_renderArticle", ["id" => $updatedComment["articleId"]]);
        }
    }

    public function deleteMethod()
    {
        $this->id       = $this->getGet("id");
        $this->comment  = ModelFactory::getModel("Comment")->readData($this->id, "id");

        ModelFactory::getModel("Comment")->deleteData($this->id);
        $this->setSession([
            "alert"     => "success",
            "message"   => "Commentaire supprimé"
        ]);
        $this->redirect("article_get", ["id" => $this->comment["articleId"]]);
    }

    public function confirmDeleteMethod()
    {
        $this->id           = $this->getGet("id");
        $this->comment      = ModelFactory::getModel("Comment")->readData($this->id, "id");
        $this->article      = ModelFactory::getModel("Article")->readData($this->comment["articleId"], "id");
        $this->loggedUser   = $this->getSession()["user"];

        return $this->twig->render("alert/commentAlertDelete.twig", [
            "article"       => $this->article,
            "comment"       => $this->comment,
            "loggedUser"    => $this->loggedUser
        ]);
    }

}
