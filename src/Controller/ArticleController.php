<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use RuntimeException;

class ArticleController extends MainController
{
    public function defaultMethod()
    {

    }

    public function newArticleMethod()
    {
        $loggedUser = $this->getSession("user");

        return $this->twig->render("article/articleCreate.twig", ["loggedUser" => $loggedUser]);
    }

    public function createMethod()
    {
        $destination = new ServiceController();
        $article = [
            "title"     => $this->encodeString($this->getPost("title")),
            "content"   => $this->encodeString($this->getPost("content")),
            "imgUrl"    => $destination->uploadFile(),
            "imgAlt"    => $this->encodeString($this->getPost("alt")),
            "createdAt" => date("Y-m-d H:i:s")
        ];

        $newArticle = ModelFactory::getModel("Article")->createData($article);

        $this->setSession([
            "alert" => "success",
            "message" => "Votre article a été créé"
        ]);
        $this->redirect("home");
    }

    public function getMethod()
    {
        $loggedUser = $this->getSession("user");
        $article = $this->getById();
        $relatedComments = ModelFactory::getModel("Comment")->listData($article["id"], "articleId");

        return $this->twig->render("article/articleDetail.twig", [
            "article" => $article,
            "loggedUser" => $loggedUser,    
            "relatedComments" => $relatedComments
        ]);
    }


    protected function getById()
    {
        $articleId = $this->getGet("id");
        $article = ModelFactory::getModel("Article")->readData($articleId,"id");

        return $article;
    }

    public function updateMethod()
    {
        $existingArticle = $this->getById();
        $destination = $existingArticle["imgUrl"];

        if ($this->getFiles()["img"]["size"] > 0 && $this->getFiles()["img"]["size"] < 1000000) {
            $destination = new ServiceController();
            $newFile = $destination->uploadFile();
        }

        if ($this->checkInputs() === TRUE) {
            $updatedArticle = array_merge($existingArticle, $this->getPost());
      
            $updatedArticle["imgUrl"]       = $newFile ?? $existingArticle["imgUrl"];
            $updatedArticle["imgAlt"]       = $this->encodeString($this->getPost("content"));
            $updatedArticle["title"]        = $this->encodeString($updatedArticle["title"]);
            $updatedArticle["content"]      = $this->encodeString($updatedArticle["content"]);
            $updatedArticle["updatedAt"]    = date("Y-m-d H:i:s");

            ModelFactory::getModel("Article")->updateData((int) $updatedArticle["id"], $updatedArticle);
            $this->setSession([
                "alert" => "success",
                "message" => "L'article a bien été mis à jour."
            ]);

            return $this->getMethod();
        }
    }


    public function confirmDeleteMethod()
    {
        $articleId  = $this->getGet("id");
        $article    = $this->getById();
        $loggedUser = $this->getSession("user");

        return $this->twig->render("alert/alertDeleteArticle.twig", [
            "article"       => $article,
            "loggedUser"    => $loggedUser
        ]);
    }


    public function deleteMethod()
    {
        $id = $this->getGet("id");

        ModelFactory::getModel("Article")->deleteData($id);
        $this->setSession([
            "alert"     => "success",
            "message"   => "L'article a bien été supprimé."
        ]);
        $this->redirect("home");
    }

    public function modifyMethod()
    {
        $article    = $this->getById();
        $loggedUser = $this->getSession("user");

        return $this->twig->render("article/articleDetail.twig", [
            "article"       => $article,
            "loggedUser"    => $loggedUser,
            "method"        => "PUT"
        ]);
    }

}
