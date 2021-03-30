<?php

namespace App\Notification;

use App\Entity\Contact;
use Twig\Environment;

class ContactNotification
{

    /**
     * @var \SWift_Mailer
     */
    private $mailer;



    /**
     * @var Environment
     */
    private $renderer;


    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notify(Contact $contact)
    {
        $message = (new \Swift_Message($contact->getFirstname()))
            ->setSubject('Réservation')
            ->setFrom('khaled.battiche@esprit.tn')
            ->setTo('khaled.battiche@esprit.tn')
            ->setReplyTo($contact->getEmail())
            ->setBody(
            '<html>' .
            ' <body>' .
            '  Message : <p>"' . $contact->getMessage() . '"<p/>' .
            '  Numero : <p>"' . $contact->getPhone() . '"<p/>'  .
            ' </body>' .
            '</html>',
              'text/html' // Mark the content-type as HTML
        );
        $this->mailer->send($message);
    }
}
