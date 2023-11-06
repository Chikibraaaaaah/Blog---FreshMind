<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use RuntimeException;
use Swift_Message;
use Swift_Mailer;
use Swift_SmtpTransport;

class ServiceController extends GlobalsController
{

    private $receiver;

    private $subject;

    private $content;

    private $transport;

    private $message;

    private $mailer;


    public function defaultMethod()
    {
    }

    public function uploadFile()
    {

        try {
            // Undefined | Multiple Files | $this->getFiles() Corruption Attack!
            // If this request falls under any of them, treat it invalid!
                if (!isset($this->getFiles()['img']['error']) || is_array($this->getFiles()['img']['error'])) {
                    throw new RuntimeException('Paramètres invalides');
                }
            
            $this->checkFileError();

            // You should also check filesize here!
            if ($this->getFiles()['img']['size'] > 1000000) {
                throw new RuntimeException('Taille maiximale 1MB.');
            }

            $ext = $this->checkFileMime();

            $fileDestination = sprintf(
                './img/%s.%s',
                sha1_file($this->getFiles()['img']['tmp_name']),
                $ext
            );

            // You should name it uniquely!
            // On this example, obtain safe unique name from its binary data!
            if (move_uploaded_file($this->getFiles()['img']['tmp_name'], $fileDestination) === FALSE) {
                throw new RuntimeException('Il y a eu un problème lors du déplacement du fichier.');
            }

            return $fileDestination;

        } catch (RuntimeException $e) {
                echo $e->getMessage();
        }

    }

    public function checkFileError()
    {
        switch ($this->getFiles()['img']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('Aucun fichier transmis.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Taille maximale atteinte. Max : 1MB.');
        default:
            throw new RuntimeException('Erreur non identifiée.');
        }
    }

    public function checkFileMime()
    {
        // Check MIME Type by yourself!
        $fileMimeType = mime_content_type($this->getFiles()['img']['tmp_name']);
        $validMimeTypes = [
            "jpg"   => "image/jpg",
            "jpeg"  => "image/jpeg",
            "png"   => "image/png",
            "gif"   => "image/gif"
        ];

        $ext = array_search($fileMimeType, $validMimeTypes, true);

        if ($ext === false) {
            return $this->setSession(["alert" => "danger", "message" => "Format invalide."]);
        // Throw new RuntimeException('Invalid file format.')!
        }

        return $ext;
    }

    public function updatePicture()
    {
        if ($this->getFiles()["img"]["size"] > 0 && $this->getFiles()["img"]["size"] < 1000000) {
            $fileInput = new ServiceController();
            $destination = $fileInput->uploadFile();
           
            return $destination;
        }

        return $this->setSession(["alert" => "danger", "message" => "Veuillez choisir un fichier."]);
    }

    public function retrieveByEmail()
    {

       if ($this->checkInputs() === TRUE) {
            $email = $this->getPost("email");
            $user = ModelFactory::getModel("User")->readData($email,"email");

            return $user;
        }

    }


    public function sendMailMethod()
    {
        $user = $this->retrieveByEmail();

        $this->receiver = $this->getPost("email");
        $this->subject = $this->getGet("subject");
        $this->content = ($this->subject === "password") ? "Bonjour " . $user["userName"] . ",\n\nVous avez demandé de réinitialisez votre mot de passe.\nPour cela, veuillez cliquer sur le lien suivant : http://localhost:8888/Blog/BlogFinal/public/index.php?access=auth_resetPassword\nBisous carresse" : "Bonjour " . $user["userName"] . ",\n\n Votre dernier commentaire a été aprouvé par nos équipes.\n Bisous";        // Créer un objet de transport SMTP
        $this->transport = new Swift_SmtpTransport("smtp.gmail.com", 587, "tls");
        $this->transport->setUsername("tristanriedinger@gmail.com");
        $this->transport->setPassword("xvajpmjxxczmfnxb");

        // Créer un objet de messagerie
        $this->mailer = new Swift_Mailer($this->transport);

        // Créer un objet de message
        $this->message = new Swift_Message("Sujet du message");
        $this->message->setFrom("tristanriedinger@gmail.com", "Tristan Riedinger - Admin Blog");
        $this->message->setTo($this->receiver);
        $this->message->setBody($this->content);

        // Envoyer le message
        $result = $this->mailer->send($this->message);

        // Vérifier le résultat de l"envoi
        if ($result) {
            echo "Message envoyé avec succès !";
        } else {
            echo "Le message n\'a pas pu être envoyé.";
        }

    }


}
