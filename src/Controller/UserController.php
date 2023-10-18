<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;

class UserController extends MainController
{
    public function defaultMethod()
    {

    }

    public function getUserMethod()
    {
        $user = $this->getUserById();
        $alert = $this->getSession()["alert"];
        $loggedUser = $this->getSession("user");

        // echo "<pre>"; 
        // var_dump($loggedUser);
        // echo "</pre>";
        // die();

        return $this->twig->render("user/userProfile.twig", [
            "user" => $user,
            "alert" => $alert,
            "loggedUser" => $loggedUser
            
        ]);

    }

    private function getUserById()
    {
        $id = $this->getGet("id");
        $user = ModelFactory::getModel("User")->listData($id, "id")[0];
        $user["password"] = "";

        return $user;
    }

}