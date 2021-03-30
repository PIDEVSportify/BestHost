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
            ->setFrom('noreply@server.fr')
            ->setTo('reserver@maison.fr')
            ->setReplyTo($contact->getEmail())
            ->setBody($contact->getMessage());
        $this->mailer->send($message);
    }
}
