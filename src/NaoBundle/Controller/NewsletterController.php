<?php

namespace NaoBundle\Controller;

use NaoBundle\Entity\Newsletter;
use NaoBundle\Form\NewsletterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class NewsletterController extends Controller
{
    /**
     * @Route("/newsletter", name="newsletter")
     */
    public
    function newsletterAction()
    {
        // Création du formulaire de la newsletter
        $newsletter = new Newsletter();
        $form = $this->createForm(
            NewsletterType::class,
            $newsletter,
            array (
                'action' => $this->generateUrl('ajax_newsletter')
            )
        );

        return $this->render(
            '_newsletter_form.html.twig',
            array (
                'form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/ajax/newsletter", name="ajax_newsletter")
     * @Method("POST")
     */
    public
    function ajaxNewsletterAction(
        Request $request
    ) {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array ('message' => 'Vous pouvez accéder à ceci uniquement en utilisant AJAX!'), 400);
        }
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $newsletterRepository = $em->getRepository('NaoBundle:Newsletter');
            $isNewsletter = $newsletterRepository->findOneBy(array ('email' => $newsletter->getEmail()));
            if (!$isNewsletter) { // Enregistrement de l'email indiqué si il n'est pas déjà enregistré
                $em->persist($newsletter);
                $em->flush();
            }
            $title = "Inscription à la newsletter réussie";
            $body = "Vous êtes maintenant inscrit à la newsletter !";
        } else { // Si le formulaire n'est pas valide
            $title = "Echec de l'inscription à la newsletter";
            $body = "L'adresse email indiquée n'est pas valide.";
        }

        return new JsonResponse(array ('title' => $title, 'body' => $body), 200);
    }
}



