<?php

namespace NaoBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;



/**
* Class RechercheOiseauController
* @package NaoBundle\Controller
*/
class RechercheOiseauController extends Controller
{

    /**
     * @Route("/recherche-oiseau", name="recherche_oiseau",defaults={"_format"="json"})
     * @Method("GET")
     */
    public function rechercheOiseauAction(Request $request)
    {
        $q = $request->query->get('q', $request->query->get('term', ''));
        $oiseaux = $this->getDoctrine()->getRepository('NaoBundle:Especes')->findLike($q);
        $results = array_unique($oiseaux);
        return $this->render('NaoBundle:Recherche:rechercheOiseau.json.twig', ['oiseaux' => $results]);
    }

    /**
     * @Route("/get-oiseau/{id}", name="get_oiseau", defaults={"_format"="json"})
     * @Method("GET")
     */
   public function getOiseauAction($id = null)
    {
        $em = $this->getDoctrine()->getManager();
        if (null === $oiseau = $em->getRepository('NaoBundle:Especes')->find($id))
        {
            throw $this->createNotFoundException();
        }

        return $this->json($oiseau->getNomVern());

    }


/*    public function getOiseauAction($id = null)
    {
        if (is_null($oiseau = $this->getDoctrine()->getRepository('NaoBundle:Especes')->find($id))) ;
        {
            throw $this->createNotFoundException();
        }

        return $this->json($oiseau->getNomVern());

    }

*/

    /**
     *
     * @return \Symfony\Component\HttpFoundation\Response $response
     * Que faisons nous si nous voulons rechercher un oiseau en connaissant la famille
     * @Route("/recherche/family", methods={"POST", "GET"}, name="rechercheAvecFamille")
     *
     */
    public function rechercheAvecFamille(Request $request)
    {
        $family = $request -> get ('family');

        $em = $this->getDoctrine()->getManager();
        $oiseaux = $em->getRepository('NaoBundle:Especes')->getOiseauxDeFamille($family);
        if ($oiseaux == null){
            $response = new JsonResponse([], 422);
        }else{
            $response = new JsonResponse(['oiseaux'=>$oiseaux], 200);
        }
        return $response;
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\Response $response
     * Que faisons nous si nous voulons rechercher une famille en connaissant l'ordre
     * @Route("/recherche/order", methods={"POST", "GET"}, name="rechercheAvecOrdre")
     *
     */
    public function rechercheAvecOrdre(Request $request)
    {
        $order = $request -> get ('order');

        $em = $this->getDoctrine()->getManager();
        $families = $em->getRepository('NaoBundle:Especes')->getFamilleByOrdre($order);
        if ($families == null){
            $response = new JsonResponse([], 422);
        }else{
            $response = new JsonResponse(['families'=>$families], 200);
        }
        return $response;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response $response
     * Que faisons nous si nous voulons trouver des observations acceptées en connaissant l'oiseau
     * @Route("/recherche/oiseau/accepte", methods={"POST", "GET"}, name="rechercheAvecOiseauAccepte")
     */

    public function trouverOiseauxAvecObservationAccepte(Request $request)
    {
        $oiseauField = $request -> get ('oiseauField');

        $em = $this->getDoctrine()->getManager();
        $observations = $em->getRepository('NaoBundle:Observation')->trouverAvecNomOiseauAccepte ($oiseauField);


//        $observationCount = $em->getRepository('NaoBundle:Observation')->getNbObservationsAvecNomOiseauAccepte($oiseauField);
//        $NbObservationsAvecNomOiseauAccepte = count($observationCount);

        if ($observations == null){
            $response = new JsonResponse([], 422);
        }else{
            $response = new JsonResponse(['observations' => $observations], 200);
        }
        return $response;

    }


    /**
     *
     * @return \Symfony\Component\HttpFoundation\Response $response
     * Que faisons nous si nous voulons trouver des observations en attente en connaissant l'oiseau
     * @Route("/recherche/oiseau/attente", methods={"POST", "GET"}, name="rechercheAvecOiseauEnAttente")
     *
     */
    public function trouverOiseauxAvecObservationsEnAttente(Request $request)
    {
        $oiseauField = $request -> get ('oiseauField');

        $em = $this->getDoctrine()->getManager();
        $observations = $em->getRepository('NaoBundle:Observation')->trouverAvecNomOiseauAttente ($oiseauField);

        if ($observations == null){
            $response = new JsonResponse([], 422);
        }else{
            $response = new JsonResponse(['observations'=>$observations], 200);
        }
        return $response;
    }

    /**
     *
     *  @return \Symfony\Component\HttpFoundation\Response $response
     * Que faisons-nous si nous voulons obtenir tout les observations de l'utilisateur
     * @Route("/admin/more", methods={"POST", "GET"}, name="moreObservationsPage")
     *
     */
    public function trouverToutObservationsUtilisateur(Request $request)
    {
        $incre = $request->get('incre');

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $observations = $em->getRepository('NaoBundle:Observation')->trouverToutEspecesIdUtilisateur($user->getId(), $incre);
        if ($observations == null){
            $response = new JsonResponse([], 422);
        }else{
            $response = new JsonResponse(['observations'=>$observations], 200);
        }
        return $response;
    }
}