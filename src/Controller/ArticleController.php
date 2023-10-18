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

    public function createArticleMethod()
    {

        $destination = new ServiceController();
        $article = [
            "title"     => $this->encodeString($this->getPost("title")),
            "content"   => $this->encodeString($this->getPost("content")),
            "imgUrl"    => $destination->uploadFile(),
            "imgAlt"    => $this->encodeString($this->getPost("alt")),
            "createdAt" => date("Y-m-d H:i:s")
        ];

        ModelFactory::getModel("Article")->createData($article);
        $this->setSession([
            "alert" => "success",
            "message" => "Votre article a été créé"
        ]);
        $this->redirect("home");


    }

    public function getArticleMethod()
    {

        $article = $this->getArticleById();

        return $this->twig->render("article/articleDetail.twig", [
            "article" => $article
        ]);

    }


    protected function getArticleById()
    {
        $articleId = $this->getGet("id");
        $article = ModelFactory::getModel("Article")->readData($articleId,"id");

        return $article;
    }

    public function updateArticleMethod()
    {
        $existingArticle = $this->getArticleById();
        $destination = $existingArticle["imgUrl"];

        if ($this->getFiles()["img"]["size"] > 0 && $this->getFiles()["img"]["size"] < 1000000) {
            $destination = new ServiceController();
            $newFile = $destination->uploadFile();
        }

        if ($this->checkInputs() === TRUE) {
            $updatedArticle = array_merge($existingArticle, $this->getPost());
            $updatedArticle["imgUrl"] = $newFile;
            $updatedArticle["imgAlt"]       = $this->encodeString($this->getPost("content"));
            $updatedArticle["title"]        = $this->encodeString($updatedArticle["title"]);
            $updatedArticle["content"]      = $this->encodeString($updatedArticle["content"]);
            $updatedArticle["updatedAt"]    = date("Y-m-d H:i:s");

            ModelFactory::getModel("Article")->updateData((int) $updatedArticle["id"], $updatedArticle);

            $this->redirect("article_renderArticle", [
                "id" => (int) $updatedArticle["id"]
            ]);
        }
    }

}