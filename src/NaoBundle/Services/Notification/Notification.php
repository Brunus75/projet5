<?php

namespace NaoBundle\Services\Notification;


use NaoBundle\Entity\Observation;
use NaoMembresBundle\Entity\User;


class Notification extends \Twig_Extension
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var
     */
    private $twig;




    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;

    }

    //Envoi d'un mail à un observateur lors d'une observation

    /**
     * @param Observation $observation
     */
    public function sendMailPostObservation(Observation $observation)
    {

        $message = \Swift_Message::newInstance()
            ->setSubject('NA0 : Votre observation est enregistrée')
            ->setFrom(array('noreply.asso_nao@gmail.com' => 'Association NAO'))
            ->setTo($observation->getUser()->getEmail())
            ->setBody(
                $this->twig->render('Emails/mailPostObservation.html.twig', array('observation' => $observation)), 'text/html')
        ;
        $this->mailer->send($message);
    }

    //Envoi d'un mail aux administrateurs lors d'une nouvelle observation
    public function sendMailNewObservation(Observation $observation, User $user)
    {

        $message = \Swift_Message::newInstance()
            ->setSubject('Nouvelle Observation')
            ->setFrom(array('noreply.asso_nao@gmail.com' => 'Association NAO'))
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render('Emails/mailNewObservation.html.twig', array(
                    'user' => $user,
                    'observation' => $observation
                )), 'text/html')
        ;
        $this->mailer->send($message);

    }

    //Envoi d'un mail à un observateur après validation d'une observation
    public function sendMailValidationObservation(Observation $observation)
    {

        $message = \Swift_Message::newInstance()
            ->setSubject('NA0 : Félicitations ! Votre observation est publiée')
            ->setFrom(array('noreply.asso_nao@gmail.com' => 'Association NAO'))
            ->setTo($observation->getUser()->getEmail())
            ->setBody(
                $this->twig->render('Emails/mailValidationObservation.html.twig', array('observation' => $observation)), 'text/html')
        ;
        $this->mailer->send($message);
    }


}
