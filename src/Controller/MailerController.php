<?php

namespace App\Controller;

use App\Controller\MainController;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport\Dsn;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MailerController extends MainController
{

    private $transport;

    private $mailer;

    private string $receiver;

    private $email;


    public function sendEmailMethod($receiver, $subject, $message)
    {
   
        // Create a Transport object
        $this->transport = Transport::fromDsn($this->getEnv("MAILER_DSN"));

         
        // Create a Mailer object
        $this->mailer = new Mailer($this->transport); 
         
        // Create an Email object
        $this->email = (new Email());
         
        // Set the "From address"
        $this->email->from('alexisbateaux@gmail.com');
         
        // Set the "From address"
        $this->email->to($receiver);

        
         
        // Set a "subject"
        $this->email->subject($subject);
         
        // Set the plain-text "Body"
        $this->email->text($message);
         
        // Set HTML "Body"
        // $this->email->html('This is the HTML version of the message.<br>Example of inline image:<br><img src="cid:nature" width="200" height="200"><br>Thanks,<br>Admin');
         
        $this->email->html($this->twig->render("email/welcome.twig"));
        // // Add an "Attachment"
        // $email->attachFromPath('/path/to/example.txt');
         
        // // Add an "Image"
        // $email->embed(fopen('/path/to/mailor.jpg', 'r'), 'nature');
         
        // Send the message
        $this->mailer->send($this->email);
    }

    public function testMethod(){
        $this->sendEmailMethod("etcacaexisteputin@gmail.com", "Félicitations !", "Votre compte a été approuve. Vous pouvez maintenant vous connecter.");
        die();
    }
}