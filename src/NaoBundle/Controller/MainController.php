<?php

namespace NaoBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Class MainController
 * @package NaoBundle\Controller
 */
class MainController extends Controller
{
    /**
     * Ce que l'on peut faire sur la homepage
     * @Route("/home", name="homepage")
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();
        $lastObservations= $em ->getRepository('NaoBundle:Observation')->trouverDernierObservations();


        $observationCount = $em->getRepository('NaoBundle:Observation')->getNbObservations();
        $especeCount = $em->getRepository('NaoBundle:Especes')->getNbEspeces();

        return $this->render('NaoBundle:Front:index.html.twig', [
            'Observations'      => $lastObservations,

        ]);
    }



    /**
     * Ce que l'on peut faire sur la page commentPage
     * @route("/comment", name="commentPage")
     */
    public function commentAction()
    {
        return $this->render('NaoBundle:Front:comment.html.twig', [

        ]);
    }



}
