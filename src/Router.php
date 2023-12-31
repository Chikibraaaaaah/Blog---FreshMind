<?php

namespace App;

/**
 * Class Router
 * Manages Routes to show Views
 * @package App
 */
class Router
{
    /**
     * Default path to all controllers
     */

    const DEFAULT_PATH = "App\Controller\\";

    /**
     * Default controller
     */

    const DEFAULT_CONTROLLER = "HomeController";

    /**
     * Default method
     */

    const DEFAULT_METHOD = "defaultMethod";

    /**
     * Requested Controller
     * @var string
     */
    private $controller = self::DEFAULT_CONTROLLER;

    /**
     * Requested Method
     * @var string
     */
    private $method = self::DEFAULT_METHOD;

    /**
     * Router constructor
     * Parses the URL, sets the Controller & his Method
     */
    public  function __construct()
    {
        $this->parseUrl();
        $this->setController();
        $this->setMethod();
    }


    /**
     * Parses the URL to get the Controller & his Method
     *
     * @return void
     */
    public function parseUrl()
    {
        $access = filter_input(INPUT_GET, "access");

        if (isset($access) === FALSE) {
            $access = "home";
        }

        $access = explode("_", $access);
        $this->controller = $access[0];

        if (count($access) === 1) {
            $this->method = "default";
        } else {
            $this->method = $access[1];
        }
    }


    /**
     * Sets the requested Controller
     * @return void
     */
    public function setController()
    {
        $this->controller = ucfirst(strtolower($this->controller))."Controller";
        $this->controller = self::DEFAULT_PATH.$this->controller;

        if (class_exists($this->controller) === FALSE) {
            $this->controller = self::DEFAULT_PATH.self::DEFAULT_CONTROLLER;
        }
    }


    /**
     * Sets the requested Method
     */
    public function setMethod()
    {
        $this->method = strtolower($this->method)."Method";

        if (method_exists($this->controller, $this->method) === FALSE) {
            $this->method = self::DEFAULT_METHOD;
        }
    }


    /**
     * Creates the Controller object & calls the Method on it
     */
    public function run()
    {
        $this->controller   = new $this->controller();
        $response           = call_user_func([$this->controller, $this->method]);

        echo filter_var($response);
    }


}
