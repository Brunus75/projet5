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
 * Class LoginController
 * @package NaoBundle\Controller
 */
class LoginController extends Controller
{

    /**
     * Ce que l'on peut faire sur la page login
     * @route("/login", name="loginPage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        $user = $this->getUser();
        /// Si l'utilisateur est déjà connecté, il est redirigé sur adminPage
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('homepage');
        }

        //Nous utilisons ceci pour obtenir des erreurs
        $authenticationUtils = $this->get('security.authentication_utils');
        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        $participer = false;

        return $this->render('NaoBundle:Front:login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'csrf_token' => $csrfToken,
            'participer'=>$participer,
            'user'=>$user
        ]);
    }
}
