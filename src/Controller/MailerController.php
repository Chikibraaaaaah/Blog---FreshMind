<?php

namespace App\Controller;

use Symfony\Component\Mailer\Transport;
use App\Controller\MainController;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

class MailerController extends MainController
{
    // #[Route('/email')]
    public function sendEmailMethod()
    {
   
        // Create a Transport object
        $transport = Transport::fromDsn($this->getEnv("MAILER_DSN"));
         
        // Create a Mailer object
        $mailer = new Mailer($transport); 
         
        // Create an Email object
        $email = (new Email());
         
        // Set the "From address"
        $email->from('alexisbateaux@gmail.com');
         
        // Set the "From address"
        $email->to('tristanriedinger@gmail.com');
         
        // Set a "subject"
        $email->subject('Demo message using the Symfony Mailer library.');
         
        // Set the plain-text "Body"
        $email->text('This is the plain text body of the message.\nThanks,\nAdmin');
         
        // Set HTML "Body"
        $email->html('This is the HTML version of the message.<br>Example of inline image:<br><img src="cid:nature" width="200" height="200"><br>Thanks,<br>Admin');
         
        // // Add an "Attachment"
        // $email->attachFromPath('/path/to/example.txt');
         
        // // Add an "Image"
        // $email->embed(fopen('/path/to/mailor.jpg', 'r'), 'nature');
         
        // Send the message
        $mailer->send($email);
    }
}