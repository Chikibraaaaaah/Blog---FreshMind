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

    private $articles = [];

    private $alert = [];

    private $loggedUser = [];


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
        $this->articles = ModelFactory::getModel("Article")->listData();
        $this->loggedUser = $this->getSession("user") ?? [];
        $this->alert = $this->getSession()["alert"] ?? [];

        return $this->twig->render("home.twig", [
            "articles" => $this->articles,
            "loggedUser" => $this->loggedUser,
            "alert" => $this->alert
        ]);
    }

    public function aboutMethod()
    {
        $this->loggedUser = $this->getSession("user");

        return $this->twig->render('about.twig', ["loggedUser" => $this->loggedUser]);
    }

    public function contactMethod()
    {
        $this->loggedUser = $this->getSession("user");

        return $this->twig->render('form.twig', ["loggedUser" => $this->loggedUser]);
    }




}
