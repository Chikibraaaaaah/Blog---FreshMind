<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Class MainController
 * Manages the Main Features
 * @package App\Controller
 */
abstract class MainController extends GlobalsController
{

    /**
     * @var Environment|null
     */
    protected $twig = null;

    /**
     * MainController constructor
     * Creates the Template Engine & adds its Extensions
     */
    public function __construct()
    {
        parent::__construct();
        $this->twig = new Environment(new FilesystemLoader("../src/View"), ["cache" => false]);
    }


    /**
     * Redirects to another URL
     * @param string $page
     * @param array $params
     * @return string $redirectUrl
     **/
    public function redirect(string $page, array $params = [])
    {
        $params["access"] = $page;
        $url = "index.php?".  htmlspecialchars(http_build_query($params));
        header("Location: index.php?" . $url);

        exit;
    }
    

    /**
     * Encodes a string by adding slashes to special characters.
     *
     * @param string $string The string to be encoded.
     * @return string The encoded string.
     */
    public function encodeString(string $string)
    {
        return addslashes($string);
    }

}
