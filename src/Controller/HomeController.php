<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class HomeController
 * Manages the Homepage
 * @package App\Controller
 */
class HomeController extends MainController
{
    /**
     * Renders the View Home
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */

    /**
     * Retrieves the default articles, user, alert, and comments to render the home view.
     * @return string The rendered home view.
     */
    public function defaultMethod()
    {
        $articles = ModelFactory::getModel("Article")->listData();
        $user = $this->getSession("user") ?? [];

        return $this->twig->render("home.twig", [
            "articles" => $articles,
            "user" => $user
        ]);
    }

    public function aboutMethod()
    {
        return $this->twig->render('about.twig');
    }

    public function contactMethod()
    {
        return $this->twig->render('form.twig');
    }




}
