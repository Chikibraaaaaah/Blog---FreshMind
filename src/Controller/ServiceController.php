<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use RuntimeException;

class ServiceController extends GlobalsController
{

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


}
