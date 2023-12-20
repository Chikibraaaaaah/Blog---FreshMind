<?php

// src/Controller/MailerController.php
namespace App\Controller;

use Dotenv\Dotenv;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

class MailerController extends MainController
{

    private $mailer;


    public function __construct()
    {
 
    }

    public function sendEmailMethod(): Response
    {

        // $email = (new Email())
        //     ->from('hello@example.com')
        //     ->to('alexisbateaux@gmail.com')
        //     //->cc('cc@example.com')
        //     //->bcc('bcc@example.com')
        //     //->replyTo('fabien@example.com')
        //     //->priority(Email::PRIORITY_HIGH)
        //     ->subject('Time for Symfony Mailer!')
        //     ->text('Sending emails is fun again!')
        //     ->html('<p>See Twig integration for better HTML integration!</p>');

        //     // $dsn = "smtp://48615331c95d40:36e5e5e545e4b3@sandbox.smtp.mailtrap.io:2525";

        //     // $transport = Transport::fromDsn($dsn);


        // $mailer->send($email);

        // var_dump($this->mailer);
        // die();

        // echo "<pre>";
        // var_dump($this->getEnv());
        // echo "</pre>";
        // die();

    }


}