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

    private $emailObject;


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

       $this->email->html($this->twig->render($this->template, ["params" => $this->params]));
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
        $this->params = $this->getPost();
        $this->email["receiver"] = "alexisbateaux@gmail.com";
        $this->email["subject"] = $this->params["precision"];
        $this->email["content"] = $this->params["demande"];

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

        $userController = new UserController();
        $userFound      = $userController->getByEmail();

        if( $userFound === FALSE) {
            $this->setSession([
                "alert"     => "danger",
                "message"   => "Cet email est inconnu de nos services."
            ]);

            sleep(2);
            $this->redirect("auth_createAcccount");
        }
        $auth           = new AuthController();
        $newPassword    = $auth->generateNewPassWordMethod();
        ModelFactory::getModel("User")->updateData($userFound["id"], ["password" => password_hash($newPassword, PASSWORD_DEFAULT)]);

        $this->params = ["user" => $userFound, "newPassword" => $newPassword];

        $this->sendEmailMethod(
            $userFound["email"],
            "Mot de passe oublie",
            "Votre nouveau mot de passe est : ".$newPassword . "nous vous inviterons à le changer une fois connecté.",
            "email/password.twig"
        );

        $this->setSession([
            "alert"     => "success",
            "message"   => "Votre message a bien été envoyé !"
        ]);


            $this->redirect("auth_register");
    }


}