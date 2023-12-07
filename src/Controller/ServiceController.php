<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use RuntimeException;

class ServiceController extends GlobalsController
{

    private $extension;

    private $MIME;

    private $destination;

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

            $this->extension = $this->checkFileMime();

            $this->destination = sprintf(
                './img/%s.%s',
                sha1_file($this->getFiles()['img']['tmp_name']),
                $this->extension
            );

            // You should name it uniquely!
            // On this example, obtain safe unique name from its binary data!
            if (move_uploaded_file($this->getFiles()['img']['tmp_name'], $this->destination) === FALSE) {
                throw new RuntimeException('Il y a eu un problème lors du déplacement du fichier.');
            }

            return $this->destination;

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
        $this->MIME = mime_content_type($this->getFiles()['img']['tmp_name']);
        $validMimeTypes = [
            "jpg"   => "image/jpg",
            "jpeg"  => "image/jpeg",
            "png"   => "image/png",
            "gif"   => "image/gif"
        ];

        $this->extension = array_search($this->MIME, $validMimeTypes, true);

        if ($this->extension === false) {
            return $this->setSession([
                "alert"     => "danger",
                "message"   => "Format invalide."
            ]);
        // Throw new RuntimeException('Invalid file format.')!
        }

        return $this->extension;
    }

    public function updatePicture()
    {
        if ($this->getFiles()["img"]["size"] > 0 && $this->getFiles()["img"]["size"] < 1000000) {
            $fileInput = new ServiceController();
            $this->destination = $fileInput->uploadFile();
           
            return $this->destination;
        }

        return $this->setSession([
            "alert"     => "danger",
            "message"   => "Veuillez choisir un fichier."
        ]);
    }

}
