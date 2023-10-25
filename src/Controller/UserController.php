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
        $alert =  $this->getSession("alert") ?? [];
        $loggedUser = $this->getSession("user");

        return $this->twig->render("user/userProfile.twig", [
            "user" => $user,
            "alert" => $alert,
            "loggedUser" => $loggedUser,
            "method" => "GET"
        ]);
    }

    private function getUserById()
    {
        $id = $this->getGet("id");
        $user = ModelFactory::getModel("User")->readData($id, "id",["password"]);

        return $user;
    }

    public function updatePictureMethod()
    {
        
        $fileInput = new ServiceController();
        $destination = $fileInput->updatePicture();
        $user = $this->getUserById();
        $user["imgUrl"] = $destination;

        ModelFactory::getModel("User")->updateData((int) $user["id"], $user);
     
        $this->setSession([
            "alert" => "success",
            "message" => "Votre photo de profil a bien été mise à jour."
        ]);

        return $this->getUserMethod();

    }

    public function editUserProfileMethod()
    {
        return $this->twig->render("user/userProfile.twig", [
            "user" => $this->getUserById(),
            "alert" =>  $this->getSession("alert") ?? [],
            "loggedUser" => $this->getSession("user"),
            "method" => "PUT"
        ]);
    }

    public function updateUserMethod()
    {
        $user = $this->getUserById();

        if ($this->checkInputs() === TRUE) {
            $updatedUser = array_merge($user, $this->getPost());
            ModelFactory::getModel("User")->updateData((int) $updatedUser["id"], $updatedUser);

            return $this->getUserMethod();
        }
    }



}