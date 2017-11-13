<?php

namespace NaoBundle\Controller;

//use NAOMembresBunde\Entity\User;
use NaoBundle\Repository\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use NaoBundle\Repository\ObservationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    /**
     * Ce que l'on peut faire sur la page adminPage
     * @Route("/admin", name="adminPage")
     */
    public function indexAction()
    {
        $user = $this->getUser();

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('Merci de vous inscrire');
        }

        $em = $this->getDoctrine()->getManager();

        $listObservations = $em
            ->getRepository('NaoBundle:Observation')
            ->trouverIdUtilisateurAvecEspeces ($user->getId());

        $observationsAValider = $em
            ->getRepository('NaoBundle:Observation')
            ->nombreObservationAValider();
        $nombreObservationAValider = count($observationsAValider);

        if(null === $user){

            return $this->redirectToRoute('loginPage');
        } else {

            return $this->render('NaoBundle:Admin:index.html.twig', [
                'Observations'=>$listObservations,
                'nombreObservationAValider'=>$nombreObservationAValider
            ]);
        }
    }

    /**
     * Ce que l'on peut faire sur la page admin pour valider les observations
     * @param Request $request
     * @Route("/admin/valider/observations/{page}", requirements={"page" = "\d+"}, name="adminValiderObservationPage")
     *@return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function validerObservationAction(Request $request)
    {

        $user = $this->getUser();

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('Vous devez être inscrit!');
        }

        $page = (int)$request->get('page');

        $em = $this->getDoctrine()->getManager();
        $observationsAValider = $em->getRepository('NaoBundle:Observation')->trouverObservationsAValider($page);
        $nombreObservationAValider = $em->getRepository('NaoBundle:Observation')->nombreObservationAValider();
        $oiseaux = $em->getRepository('NaoBundle:Especes')->getOiseaux();

        $nbObervationsAValider = count($nombreObservationAValider);
        $perPage = 10;
        $nbPagesFloat = $nbObervationsAValider / $perPage;
        $nbPages = ceil($nbPagesFloat);

        if (null === $user) {

            return $this->redirectToRoute('loginPage');

        }

//            Indiquer la conditions de la validation du ROLE

 /*           elseif ($user->get............() === false) {

            return $this->redirectToRoute('adminPage');

        } */

        return $this->render('NaoBundle:Admin:validerObservations.html.twig', [
            'observationsAValider'=>$observationsAValider,
            'oiseaux'=>$oiseaux,
            'nbPages'=>$nbPages,
            'user' =>$user
        ]);
    }

    /**
     * Ce que l'on peut faire sur la page pour valider une observation
     * @param $observationId
     * @param Request $request
     * @Route("/admin/valider/observations/{page}/confirm/{observationId}", methods={"POST", "GET"}, requirements={"observationId" = "\d+"}, name="confirmeObservation")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

 public function confirmeObservation(Request $request, $observationId)
    {
        $em = $this->getDoctrine()->getManager();
        $observation = $em->getRepository('NaoBundle:Observation')->find($observationId);

        if(!$observation){
            throw $this->createNotFoundException(
                'L\'observation d\'id '.$observationId.' n\'a pas été trouvée.'
            );
        }

        $currentPage = $request->get('page');

        $observation->setStatut("accepte");
        $em->flush();
        $this->addFlash(
            'info',
            'L\'observation a été validée et publiée.'
        );

        return $this->redirectToRoute('adminValiderObservationPage', ['page' => $currentPage]);
    }

    /**
     * Ce que l'on peut faire sur la page de refus d'observations
     * @param $observationId
     * @param Request $request
     * @Route("/admin/valider/observations/{page}/refuse/{observationId}", methods={"POST", "GET"}, requirements={"observationId" = "\d+"}, name="refuseObservation")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function refuseObservation(Request $request, $observationId)
    {
        $em = $this->getDoctrine()->getManager();
        if(!$observationId){
            throw $this->createNotFoundException(
                'L\'observation d\'id '.$observationId.' n\'a pas été trouvée.'
            );
        }

        $currentPage = $request->get('page');

        $em->getRepository('NaoBundle:Observation')->supprimerObservation($observationId);

        $this->addFlash(
            'warning',
            'L\'observation a été supprimée et ne sera pas publiée.'
        );

        return $this->redirectToRoute('adminValiderObservationPage', ['page'=>$currentPage]);
    }
}
