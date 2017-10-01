<?php

namespace NaoBundle\Controller;

use NaoBundle\Entity\Observation;
use NaoBundle\Entity\Especes;
use NAOMembresBundle\Entity\User;
use NaoBundle\Form\ObservationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;



/**
 * Class FrontController
 * @package NaoBundle\Controller
 */
class FrontController extends Controller
{
    /**
     * e que l'on peut faire sur la page d'accueil
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        if($this->getUser()){
            $gravatar = $this->getUser()->getGravatarPicture();
        } else {
            $gravatar = null;
        }

        $em = $this->getDoctrine()->getManager()->getRepository('NaoBundle:Observation');
        $lastObservations = $em->findLastObservations();
        return $this->render('NaoBundle:Front:index.html.twig',[
            'Observations'=>$lastObservations,
            'gravatar'=>$gravatar
        ]);
    }


    /**
     * @param Request $request
     * e que l'on peut faire sur la page Observation
     * @route("/add", name="addPage")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $user = $this->getUser();

        if($user){
            $gravatar = $this->getUser()->getGravatarPicture();
        } else {
            $gravatar = null;
        }

        $observation = new Observation();

        $observation->setUser($user);

        // Grant status of the observation  according to ROLE
        if ($this->isGranted('ROLE_ADMININSTRATEUR') || ($this->isGranted('ROLE_ORNITHOLOGUE') && $user->getIsAccredit())) {
            $observation->setStatut('accepté');
        } else {
            $observation->setStatut('en attente');
        }

        $form = $this->createForm(ObservationType::class, $observation, ['method'=>'PUT']);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()){

            $this->getDoctrine()->getRepository('NaoBundle:Observation')->add($observation);

            //Augmenter l'utilisateur xp avec chaque observation
            $userXp = $user->getXp();
            $user->setXp($userXp+100);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            if ($observation->getStatut() === 'accepté'){
                $this->addFlash('info', 'Votre observation a été enregistrée.');
            } elseif ($observation->getStatut() === 'en attente'){
                $this->addFlash('info', 'Votre observation a été enregistrée, elle est en attente de validation.');
            }

            return $this->redirectToRoute('homepage');
        }

        $participate = true;

        if(null === $user) {
            return $this->redirectToRoute('login', [
                'participate' => $participate
            ]);
        } else {
            return $this->render('NaoBundle:Front:add.html.twig', array(
                'form' => $form->createView(),
                'gravatar' => $gravatar
            ));
        }

    }

    /**
     * Ce que l'on peut faire sur la page recherche
     * @route("/search", name="searchPage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request)
    {
        if($this->getUser()){
            $gravatar = $this->getUser()->getGravatarPicture();
        } else {
            $gravatar = null;
        }

        $em = $this->getDoctrine()->getManager();
        $ordres = $em->getRepository('NaoBundle:Especes')->getOrdre();
        $familles = $em->getRepository('NaoBundle:Especes')->getFamille();
        $birds = $em->getRepository('NaoBundle:Especes')->getBirds();

        return $this->render('NaoBundle:Front:search.html.twig', [
            'gravatar'=>$gravatar,
            'ordres'=>$ordres,
            'familles'=>$familles,
            'birds'=>$birds
        ]);
    }

    /**
     * e que l'on peut faire sur la page Légale
     * @route("/legal", name="legalPage")
     */
    public function legalAction()
    {
        if($this->getUser()){
            $gravatar = $this->getUser()->getGravatarPicture();
        } else {
            $gravatar = null;
        }
        return $this->render('NaoBundle:Front:legal.html.twig', [
            'gravatar'=>$gravatar
        ]);
    }

    /**
     * e que l'on peut faire sur la page howPage
     * @route("/how", name="howPage")
     */
    public function howAction()
    {
        if($this->getUser()){
            $gravatar = $this->getUser()->getGravatarPicture();
        } else {
            $gravatar = null;
        }
        return $this->render('NaoBundle:Front:how.html.twig', [
            'gravatar'=>$gravatar
        ]);
    }

    /**
     * e que l'on peut faire sur la page Association
     * @route("/association", name="associationPage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function associationAction(Request $request)
    {
        if($this->getUser()){
            $gravatar = $this->getUser()->getGravatarPicture();
        } else {
            $gravatar = null;
        }

        $form = $this->createForm('NaoBundle\Form\ContactType',null,[
            'action' =>$this->generateUrl('associationPage'),
            'method'=>'POST'
        ]);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $data = $form->getData();
                $message = \Swift_Message::newInstance()
                    ->setSubject('Nouveau message de '.$data['firstname'].' '.$data['lastname'].' ('.$data['email'].') sur l\'application NAO')
                    ->setFrom($data['email'])
                    ->setTo('88brunus88@gmail.com')
                    ->setBody($form->getData()['message'],
                        'text/plain'
                    );
                $this->get('mailer')->send($message);
                $this->addFlash(
                    'success',
                    'Votre message a bien été envoyé !'
                );
            }
        }

        return $this->render('NaoBundle:Front:association.html.twig', [
            'form'=>$form->createView(),
            'gravatar'=>$gravatar
        ]);
    }

    /**
     * Ce que l'on peut faire sur la page login
     * @route("/login", name="loginPage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        if($this->getUser()){
            $gravatar = $this->getUser()->getGravatarPicture();
        } else {
            $gravatar = null;
        }

        /// Si l'utilisateur est déjà connecté, il est redirigé sur adminPage
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('adminPage');
        }

        //We use this to get errors
        $authenticationUtils = $this->get('security.authentication_utils');

        $participate = false;

        return $this->render('NaoBundle:Front:login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'gravatar'=>$gravatar,
            'participate'=>$participate
        ]);
    }

    /**
     * e que l'on peut faire sur la page recherche de coordonnées
     * @param Request $request
     * @route("/search/gps/{lat}/{lon}", methods={"GET"}, name="SearchGpsCoordinates")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function searchGpsCoordinates(Request $request){
        $lat = $request->get('lat');
        $lon = $request->get('lon');
        $this->addFlash('success_lat', $lat);
        $this->addFlash('success_lon', $lon);
        return $this->redirectToRoute('searchPage');
    }

    /**
     * Ce que l'on peut faire sur la page sheetPage
     * @param Request $request
     * @route("/sheet/{id}", methods={"GET"}, requirements={"id" : "\d+"}, name="SheetPage")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function sheetAction(Request $request)
    {
        if($this->getUser()){
            $gravatar = $this->getUser()->getGravatarPicture();
        } else {
            $gravatar = null;
        }

        $id = $request->get('id');
        $em = $this->getDoctrine()->getRepository('NaoBundle:Observation');
        $observation = $em->find($id);

        return $this->render('NaoBundle:Front:sheet.html.twig', [
            'Observation' => $observation
        ]);
    }

}
