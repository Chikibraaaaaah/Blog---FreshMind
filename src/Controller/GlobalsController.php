<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Dotenv\Dotenv;
use Twig\Error\LoaderError;
use RuntimeException;


/**
 * Class GlobalsController
 * @package App\Controller
 */
abstract class GlobalsController
{
    /**
     * @var array
     */
    private $alert = [];

    /**
     * @var array
     */
    private $file = [];

    /**
     * @var array
     */
    private $files = [];

    /**
     * @var array
     */
    private $get = [];

    /**
     * @var array
     */
    private $env = [];

    /**
     * @var array
     */
    private $dotenv = [];

    /**
     * @var array
     */
    private $dossierParent = [];

    /**
     * @var array
     */
    private $post = [];

    /**
     * @var array
     */
    private $session = [];

    /**
     * @var array
     */
    private $user = [];

    /**
     * GlobalsController Constructor
     * Assign all Globals to Properties
     * With some Checking for Files & Session
     */
    public function __construct()
    {

        $this->get              = filter_input_array(INPUT_GET) ?? [];
        $this->post             = filter_input_array(INPUT_POST) ?? [];
        $this->files            = filter_var_array($_FILES) ?? [];
        $this->dossierParent    = dirname(dirname(__DIR__));
        $this->dotenv           = Dotenv::createImmutable($this->dossierParent);
        $this->env              = array_merge(filter_input_array(INPUT_ENV), $this->dotenv->load());
        
        
        if (isset($this->files["file"]) === TRUE) {
            $this->file = $this->files["file"];
        }

        if (array_key_exists("alert", $_SESSION) === false) {
            $_SESSION["alert"] = [];
        }

        $this->session  = filter_var_array($_SESSION) ?? [];
        $this->alert    = $this->session["alert"];

        if (isset($this->session["user"]) === TRUE) {
            $this->user = $this->session["user"];
        }


    }


    /**
     * Set User Session or User Alert
     * @param array $user User information
     * @param bool $session Alert information
     * @return array|void
     */
    protected  function setSession(array $user, bool $session=false)
    {
        if ($session === false) {
            $_SESSION["alert"] = $user;
        } elseif ($session === true) {
            if (isset($user["pass"]) === TRUE) {
                unset($user["pass"]);
            } elseif (isset($user["password"]) === TRUE) {
                unset($user["password"]);
            }
            $_SESSION["user"] = $user;
        }
    }


    // ******************** CHECKERS ******************** \\
    /**
     * Check User Alert or User Session
     * @param bool $alert
     * @return bool
     */
    protected function checkUser(bool $alert=FALSE)
    {
        if ($alert === TRUE) {
            return empty($this->alert) === FALSE;
        }

        if (array_key_exists("user", $this->session) === TRUE) {
            if (empty($this->user) === FALSE) {
                return true;
            }
        }

        return false;
    }


    protected function checkInputs()
    {
        $inputs = $this->getPost();

        foreach ($inputs as $input => $value) {
            if (empty($value) === TRUE) {
                $this->setSession([
                    "alert" => "danger",
                    "message" => "Veuillez remplir le champ " . $input
                ]);
                return false;
            }
        }

        return true;
    }


    // ******************** GETTERS ******************** \\
    /**
     * Get Alert Type or Alert Message
     * @param bool $type
     * @return string|void
     */
    protected function getAlert(bool $type=false)
    {
        // if (isset($this->alert) === TRUE) {
        //     if ($type === TRUE) {
        //         return $this->alert["type"] ?? "";
        //     }

        //     echo filter_var($this->alert["message"]);

        //     unset($_SESSION["alert"]);
        // }

        $this->alert = $this->getSession()["alert"];
        unset($_SESSION["alert"]);

        return  $this->alert;
    }


    /**
     * Get Files Array, File Array or File Var
     * @param null|string $var
     * @return array|string
     */
    protected function getFiles(string $var=null)
    {
        if ($var === null) {
            return $this->files;
        }

        if ($var === "file") {
            return $this->file;
        }

        return $this->file[$var] ?? "";
    }


    /**
     * Get Get Array or Get Var
     * @param null|string $var
     * @return array|string
     */
    protected function getGet(string $var = null)
    {
        if ($var === null) {
            return $this->get;
        }

        return $this->get[$var] ??"";
    }


    /**
     * Get Post Array or Post Var
     * @param null|string $var
     * @return array|string
     */
    protected function getPost(string $var = null)
    {
        if($var === null) {
            return $this->post;
        }

        return $this->post[$var] ??"";
    }


    /**
     * Get Session Array, User Array or User Var
     * @param null|string $var
     * @return array|string
     */
    protected function getSession(string $var = null)
    {
        if ($var === null) {
            return $this->session;
        }

        if ($var ==="user") {
            return $this->user;
        }

        if (!$this->checkUser()) {
            $this->user[$var] = null;
        }

        return $this->user[$var] ??"";
    }



    /**
     * Retrieves the specified environment variable value or the entire environment array if no variable is specified.
     *
     * @param string $var (optional) The name of the environment variable to retrieve. Defaults to null.
     * @return mixed The value of the specified environment variable, or an empty string if the variable does not exist.
     */
    protected function getEnv(string $var = null)
    {
        if ($var === null) {

            return $this->env;
        }
        
        return $this->env[$var] ?? "";
    }


    // ******************** DESTROYER ******************** \\
    /**
     * Destroy $name Cookie or Current Session
     * @param string $name
     */


    protected function destroyGlobal(/*string $name = null*/)
    {
        $_SESSION["user"] = [];
        session_destroy();
    }

    
}