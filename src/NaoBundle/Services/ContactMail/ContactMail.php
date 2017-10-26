<?php


namespace NaoBundle\Services\ContactMail;

use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

class ContactMail extends \Twig_Extension
{

    private $twig;
    private $mailer;

    public function __construct(\Swift_Mailer $mailer,\Twig_Environment $twig)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    public function sendContactMail($contact)
    {
        $reply = $contact['email'];
        try {
            $message = \Swift_Message::newInstance ()->setSubject ('Contact')
                ->setFrom ('88brunus88@gmail.com')
                ->setTo (['88brunus88@gmail.com', $reply])
                ->setBody (
                    $this->twig->render (
                        ':Emails:contactMail.html.twig',
                        array (
                            'name' => $contact['name'],
                            'firstName' => $contact['firstName'],
                            'email' => $contact['email'],
                            'object' => $contact['object'],
                            'message' => $contact['message'],
                        )
                    ),
                    'text/html'
                );
        } catch (Twig_Error_Loader $e) {
        } catch (Twig_Error_Runtime $e) {
        } catch (Twig_Error_Syntax $e) {
        }
        $this->mailer->send($message);
    }

    public function sendContactMailToSender($contact)
    {
        $reply = $contact['email'];
        $message = \Swift_Message::newInstance()->setSubject('Contact')
            ->setFrom('88brunus88@gmail.com')
            ->setTo($reply)
            ->setBody($this->twig->render(
                ':Emails:contactMailForSender.html.twig',
                array(
                    'name'      => $contact['name'],
                    'firstName' => $contact['firstName'],
                    'email'     => $contact['email'],
                    'object'    => $contact['object'],
                    'message'   => $contact['message']
                )
            ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}
