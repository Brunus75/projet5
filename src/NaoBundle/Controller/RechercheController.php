<?php

namespace NaoBundle\Controller;

use NaoBundle\Entity\Observation;
use NaoBundle\Entity\Especes;
use NAOMembresBundle\Entity\User;
use NaoBundle\Repository\ObservationRepository;
use Doctrine\ORM\EntityRepository;
use NaoBundle\Form\ObservationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;



/**
 * Class RechercheController
 * @package NaoBundle\Controller
 */
class RechercheController extends Controller
{

    /**
     * Ce que l'on peut faire sur la page recherche
     * @route("/recherche", name="recherchePage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function rechercheAction(Request $request)
    {
        $user = $this->getUser();
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('Vous devez être inscrit!');
        }

        $em = $this->getDoctrine()->getManager();
        $ordres = $em->getRepository('NaoBundle:Especes')->getOrdre();
        $familles = $em->getRepository('NaoBundle:Especes')->getFamille();
        $oiseaux = $em->getRepository('NaoBundle:Especes')->getOiseaux();

        return $this->render('NaoBundle:Recherche:recherche.html.twig', array (
            'user'=>$user,
            'ordres'=>$ordres,
            'familles'=>$familles,
            'oiseaux'=>$oiseaux
        )
        );
    }

    /**
     * Ce que l'on peut faire sur la page recherche de coordonnées
     * @param Request $request
     * @route("/recherche/gps/{lat}/{lon}", methods={"GET"}, name="RechercheGpsCoordonnees")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function rechercheGpsCoordonnees(Request $request){
        $lat = $request->get('lat');
        $lon = $request->get('lon');
        $this->addFlash('success_lat', $lat);
        $this->addFlash('success_lon', $lon);
        return $this->redirectToRoute('recherchePage');
    }
}
