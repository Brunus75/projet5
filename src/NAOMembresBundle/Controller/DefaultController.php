<?php

namespace NAOMembresBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/observations", name="nao_observation")
     */
    public function indexAction()
    {
        return $this->render('NAOMembresBundle:Default:observations.html.twig');
    }
}
