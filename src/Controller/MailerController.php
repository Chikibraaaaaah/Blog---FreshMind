<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;

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

    private $email;


    /**
    * Send an email.
    *
    * @param string $receiver The email recipient.
    * @param string $subject The email subject.
    * @param string $message The email message.
    * @param string $template The path to the email template.
    * @throws Some_Exception_Class description of exception
    * @return void
    */
   public function sendEmailMethod($receiver, $subject, $message, $template)
   {
       // Create a Transport object
       $this->transport = Transport::fromDsn($this->getEnv("MAILER_DSN"));

    //    var_dump($this->getEnv());
    //    die();
       
       // Create a Mailer object
       $this->mailer = new Mailer($this->transport); 

    //    var_dump($this->transport);
    //    die();
       
       // Create an Email object
       $this->email = (new Email());
       
       // Set the "From address"
       $this->email->from('alexisbateaux@gmail.com');
       
       // Set the "to address"
       $this->email->to($receiver);
       
       // Set a "subject"
       $this->email->subject($subject);
       
       // Set the plain-text "Body"
       $this->email->text($message);
       
       // Set HTML "Body"
       // $this->email->html('This is the HTML version of the message.<br>Example of inline image:<br><img src="cid:nature" width="200" height="200"><br>Thanks,<br>Admin');
       
       $this->template = $template;

       $this->email->html($this->twig->render($this->template, ["form" => $this->form]));
       // // Add an "Attachment"
       // $email->attachFromPath('/path/to/example.txt');
       
       // // Add an "Image"
       // $email->embed(fopen('/path/to/mailor.jpg', 'r'), 'nature');
       
       // Send the message
       $this->mailer->send($this->email);
   }

   /**
    * A description of the entire PHP function.
    *
    * @throws Some_Exception_Class description of exception
    * @return void
    */
   public function contactMethod(){
       $this->form = $this->getPost();
       $this->email["receiver"] = "alexisbateaux@gmail.com";
       $this->email["subject"] = $this->form["precision"];
       $this->email["content"] = $this->form["demande"];

       $this->sendEmailMethod(
           $this->email["receiver"],
           $this->email["subject"],
           $this->email["content"],
           "email/contact.twig"
       );
       $this->setSession([
           "alert"     => "success",
           "message"   => "Votre message a bien été envoyé !"
       ]);

       $this->redirect("home");
   }

   // TODO : Fonction pour générer un mot de passe pour l'utilisateur

//    public function resetPasswordMethod(){
//     $mailer = new MailerController();

//     $user = $this->checkByEmail();
//     var_dump($user);
//     die();

//     // shuffle($this->password);

// }

public function passwordForgetMethod(){

    $email = $this->getPost("email");
    $userFound = ModelFactory::getModel("User")->readData($email, "email");

    if( $userFound === FALSE) {
        $this->setSession([
            "alert"     => "danger",
            "message"   => "Cet email est inconnu de nos services."
        ]);

        sleep(2);
        $this->redirect("auth_createAcccount");
    }

    $this->setSession([
        "alert"     => "success",
        "message"   => "Un email de récupération de mot de passe a été envoyé !"
    ]);

    sleep(2);
    $this->redirect("auth_register");
    
}


}