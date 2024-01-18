<?php

namespace App\Controller;

use App\Entity\ManagerEntity;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

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
        // $this->articles     = EntityFactory::getModel("Article")->listData();
        $this->loggedUser   = $this->getSession("user") ?? [];
        $this->alert        = $this->getAlert() ?? [];

        return $this->twig->render("home.twig", [
            "articles"      => $this->articles,
            "loggedUser"    => $this->loggedUser,
            "alert"         => $this->alert
        ]);
    }

    public function aboutMethod()
    {
        $this->loggedUser = $this->getSession("user");

        return $this->twig->render('user/about.twig', ["loggedUser" => $this->loggedUser]);
    }

    public function contactMethod()
    {
        $this->loggedUser = $this->getSession("user");

        return $this->twig->render('form.twig', ["loggedUser" => $this->loggedUser]);
    }
    
}
