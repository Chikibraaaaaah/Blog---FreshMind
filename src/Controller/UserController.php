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

        return $this->twig->render("user/userProfile.twig", [
            "user" => $user,
            "alert" => $alert
            
        ]);

    }

    private function getUserById()
    {
        $id = $this->getGet("id");
        $user = ModelFactory::getModel("User")->listData($id, "id")[0];

        return $user;
    }

}