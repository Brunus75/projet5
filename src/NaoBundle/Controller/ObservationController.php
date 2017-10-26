<?php

namespace NaoBundle\Controller;

use Doctrine\ORM\OptimisticLockException;
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
 * Class ObservationController
 * @package NaoBundle\Controller
 */
class ObservationController extends Controller
{

    /**
     * @param Request $request
     * Ce que l'on peut faire sur la page Observation participer   (nouvelle observation)
     * @route("/participer", name="participerPage")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function participerAction(Request $request)
    {
        $user = $this->getUser();

        // Création de l'entité Observation
        $observation = new Observation();

        $observation->setUser($user);

        // Octroyer l'état de l'observation selon ROLE
        if ($this->isGranted('ROLE_ADMININSTRATEUR') || ($this->isGranted('ROLE_ORNITHOLOGUE') && $user->getIsAccredit())) {
            $observation->setStatut('accepte');
        } else {
            $observation->setStatut('attente');
        }

        $form = $this->createForm(ObservationType::class, $observation, ['method'=>'PUT']);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()){
            $this->getDoctrine ()->getRepository ('NaoBundle:Observation')->ajouter ($observation);

          $em = $this->getDoctrine()->getManager();
          $em->persist($user);
          $em->flush();

            if ($observation->getStatut() === 'accepte'){
                $this->addFlash('info', 'Votre observation a été enregistrée.');
            } elseif ($observation->getStatut() === 'attente'){
                $this->addFlash('info', 'Votre observation a été enregistrée, elle est en attente de validation.');
            }

            return $this->redirectToRoute('homepage');
        }

        $participer = true;

        if(null === $user) {
            return $this->redirectToRoute('loginPage', [
                'participer' => $participer
            ]);
        } else {
            return $this->render('NaoBundle:Front:participer.html.twig', array(
                'form' => $form->createView(),
                'user' => $user,

            ));
        }

    }

    /**
     * Ce que l'on peut faire sur la page feuillePage (Information d'une observation)
     * @param Request $request
     * @route("/feuille/{id}", methods={"GET"}, requirements={"id" : "\d+"}, name="FeuillePage")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function feuilleAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException('Vous devez être inscrit!');
        }

        $id = $request->get('id');
        $em = $this->getDoctrine()->getRepository('NaoBundle:Observation');
        $observation = $em->find($id);

        return $this->render('NaoBundle:Front:feuille.html.twig', [
            'Observation' => $observation
        ]);
    }

}
