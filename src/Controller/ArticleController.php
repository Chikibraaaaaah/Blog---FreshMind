<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use RuntimeException;

class ArticleController extends MainController
{

    private $articleId;

    private $article;

    private $alert;

    private $loggedUser;

    private $destination;

    private $relatedComments;


    /**
     * A description of the entire PHP function.
     *
     * @throws Some_Exception_Class description of exception
     */
    public function defaultMethod()
    {
        $this->redirect("home");
    }


    /**
     * Generates a new article method.
     *
     * @return string The rendered template.
     */
    public function newArticleMethod()
    {
        $this->loggedUser = $this->getSession("user");
        $this->alert      = $this->getAlert() ?? [];

        return $this->twig->render("article/createArticle.twig", [
            "alert"         => $this->alert,
            "loggedUser"    => $this->loggedUser
        ]);
    }


    /**
     * Creates a new article.
     *
     * @throws Some_Exception_Class description of exception
     * @return Some_Return_Value
     */
    public function createMethod()
    {
        $this->destination    = new ServiceController();
        $this->article        = [
                                    "title"     => $this->encodeString($this->getPost("title")),
                                    "content"   => $this->encodeString($this->getPost("content")),
                                    "imgUrl"    => $destination->uploadFile(),
                                    "imgAlt"    => $this->encodeString($this->getPost("alt")),
                                    "createdAt" => date("Y-m-d H:i:s")
                                ];

        $newArticle = ModelFactory::getModel("Article")->createData($this->article);
        $this->setSession([
            "alert"     => "success",
            "message"   => "Votre article a été créé"
        ]);
        $this->redirect("home");
    }


    /**
     * Retrieves the method and returns the rendered template.
     *
     * @return string The rendered template.
     */
    public function getMethod()
    {
        $this->loggedUser         = $this->getSession("user") ?? [];
        $this->article            = $this->getById();
        $this->relatedComments    = ModelFactory::getModel("Comment")->listData($this->article["id"], "articleId");
        $this->alert              = $this->getAlert() ?? [];

        return $this->twig->render("article/getOneArticle.twig", [
            "article"           => $this->article,
            "loggedUser"        => $this->loggedUser,    
            "relatedComments"   => $this->relatedComments,
            "alert"             => $this->alert
        ]);
    }


    /**
     * Retrieves an article by its ID.
     *
     * @return mixed The article data.
     */
    protected function getById()
    {
        $articleId  = $this->getGet("id");
        $article    = ModelFactory::getModel("Article")->readData($articleId,"id");

        return $article;
    }


    /**
     * Updates the method.
     *
     * @throws Some_Exception_Class description of exception
     * @return Some_Return_Value
     */
    public function updateMethod()
    {
        $this->article = $this->getById();

        if ($this->checkInputs() === TRUE) {
            $updatedArticle = array_merge($this->article, $this->getPost());
            $updatedArticle["imgAlt"]       = $this->encodeString($this->getPost("content"));
            $updatedArticle["title"]        = $this->encodeString($updatedArticle["title"]);
            $updatedArticle["content"]      = $this->encodeString($updatedArticle["content"]);
            $updatedArticle["updatedAt"]    = date("Y-m-d H:i:s");

            ModelFactory::getModel("Article")->updateData((int) $updatedArticle["id"], $updatedArticle);
            $this->setSession([
                "alert"     => "success",
                "message"   => "L'article a bien été mis à jour."
            ]);

            return $this->getMethod();
        }
    }


    /**
     * Confirm the delete method.
     *
     * @return string The rendered view.
     */
    public function confirmDeleteMethod()
    {
        $this->articleId  = $this->getGet("id");
        $this->article    = $this->getById();
        $this->loggedUser = $this->getSession("user");

        return $this->twig->render("alert/articleDeleteAlert.twig", [
            "article"       => $this->article,
            "loggedUser"    => $this->loggedUser
        ]);
    }


    /**
     * Deletes a method.
     *
     * @throws Some_Exception_Class description of exception
     * @return Some_Return_Value
     */
    public function deleteMethod()
    {
        $this->articleId = $this->getGet("id");

        ModelFactory::getModel("Article")->deleteData($this->articleId);
        $this->setSession([
            "alert"     => "success",
            "message"   => "L'article a bien été supprimé."
        ]);
        $this->redirect("home");
    }


    /**
     * Modify the method.
     *
     * @return string The rendered template.
     */
    public function modifyMethod()
    {
        $this->article    = $this->getById();
        $this->loggedUser = $this->getSession("user");

        return $this->twig->render("article/getOneArticle.twig", [
            "article"       => $this->article,
            "loggedUser"    => $this->loggedUser,
            "method"        => "PUT"
        ]);
    }

}
