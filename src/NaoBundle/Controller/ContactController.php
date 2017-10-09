<?php

namespace NaoBundle\Controller;

use NaoBundle\Entity\Contact;
use NaoBundle\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ContactController extends Controller
{
    /**
     * @Route("/contact", name="contact")
     * @Method({"GET","POST"})
     */
    public function contactAction(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->get('nao.send_contact_mail')->sendContactMail($contact);
            $this->get('nao.send_contact_mail')->sendContactMailToSender($contact);
            $this->addFlash('info', 'Votre message a bien été envoyé, nous répondrons dès que possible à votre demande.');
            return $this->redirectToRoute('contact');
        }

        return $this->render(':Contact:contact.html.twig', array(
            'form' => $form->createView()
        ));
    }

}



