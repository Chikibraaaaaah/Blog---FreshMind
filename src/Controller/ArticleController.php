<?php

namespace App\Controller;

class ArticleController extends MainController
{
    public function defaultMethod()
    {
    }

    public function articleCreateMethod()
    {
        return $this->twig->render("article/articleCreate.twig");
    }

}