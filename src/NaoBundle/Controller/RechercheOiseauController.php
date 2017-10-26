<?php

namespace NaoBundle\Controller;


use NaoBundle\Entity\Observation;
use NaoBundle\Entity\Especes;
use NaoMembresBundle\Entity\User;
use NaoBundle\Form\ObservationType;
use Doctrine\ORM\EntityManager;
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
        return $this->render('NaoBundle:Recherche:rechercheoiseau.json.twig', ['oiseaux' => $results]);
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
     * @param Especes $especes
     * @return \Symfony\Component\HttpFoundation\Response $response
     * Que faisons nous si nous voulons rechercher un oiseau en connaissant la famille
     * @Route("/recherche/family/{family}", methods={"GET"}, name="rechercheAvecFamille")
     * @ParamConverter("especes", options={"mapping": {"family": "famille"}})
     */
    public function rechercheAvecFamille(Especes $especes)
    {
        $em = $this->getDoctrine()->getManager();
        $oiseaux = $em->getRepository('NaoBundle:Especes')->getOiseauxDeFamille($especes->getFamille());
        if ($oiseaux == null){
            $response = new JsonResponse([], 422);
        }else{
            $response = new JsonResponse(['oiseaux'=>$oiseaux], 200);
        }
        return $response;
    }

    /**
     * @param Especes $especes
     * @return \Symfony\Component\HttpFoundation\Response $response
     * Que faisons nous si nous voulons rechercher une famille en connaissant l'ordre
     * @Route("/recherche/order/{order}", methods={"GET"}, name="rechercheAvecOrdre")
     * @ParamConverter("especes", options={"mapping": {"order": "ordre"}})
     */
    public function rechercheAvecOrdre(Especes $especes)
    {
        $em = $this->getDoctrine()->getManager();
        $families = $em->getRepository('NaoBundle:Especes')->getFamilleByOrdre($especes->getOrdre());
        if ($families == null){
            $response = new JsonResponse([], 422);
        }else{
            $response = new JsonResponse(['families'=>$families], 200);
        }
        return $response;
    }

    /**
     * @param Especes $especes
     * @return \Symfony\Component\HttpFoundation\Response $response
     * Que faisons nous si nous voulons trouver des observations acceptées en connaissant l'oiseau
     * @Route("/recherche/oiseau/accepte/{id}", requirements={"id" : "\d+"}, methods={"POST", "GET"}, name="rechercheAvecOiseau")
     * @ParamConverter("observations", options={"mapping": {"id": "especes_id"}})
     */
    public function trouverOiseauxAvecObservationAccepte(Especes $especes)
    {
        $em = $this->getDoctrine()->getManager();
        $observations = $em->getRepository('NaoBundle:Observation')->trouverAvecNomOiseau($especes->getId(), 'accepte');
        if ($observations == null){
            $response = new JsonResponse([], 422);
        }else{
            $response = new JsonResponse(['observations'=>$observations], 200);
        }
        return $response;
    }

    /**
     * @param Especes $especes
     * @return \Symfony\Component\HttpFoundation\Response $response
     * Que faisons nous si nous voulons trouver des observations en attente en connaissant l'oiseau
     * @Route("/recherche/oiseau/attente/{id}", requirements={"id" : "\d+"}, methods={"POST", "GET"}, name="rechercheAvecOiseauEnattente")
     * @ParamConverter("observations", options={"mapping": {"id": "especes_id"}})
     */
    public function trouverOiseauxAvecObservationsEnattente(especes $especes)
    {
        $em = $this->getDoctrine()->getManager();
        $observations = $em->getRepository('NaoBundle:Observation')->trouverAvecNomOiseau($especes->getId(), 'attente');
        if ($observations == null){
            $response = new JsonResponse([], 422);
        }else{
            $response = new JsonResponse(['observations'=>$observations], 200);
        }
        return $response;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response $response
     * Que faisons-nous si nous voulons obtenir tout d'observations de l'utilisateur
     * @Route("/admin/more/{incre}", requirements={"incre" : "\d+"}, methods={"POST", "GET"}, name="moreObservationsPage")
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