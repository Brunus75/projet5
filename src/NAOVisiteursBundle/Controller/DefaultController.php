<?php

namespace NAOVisiteursBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="nao_accueil")
     */
    public function indexAction()
    {
        return $this->render('NAOVisiteursBundle:Default:index.html.twig');
    }
    
    /**
     * @Route("/associationN", name="nao_association")
     */
    public function associationAction()
    {
        return $this->render('NAOVisiteursBundle:Default:association.html.twig');
    }
    
    /**
     * @Route("/programmeN", name="nao_programme")
     */
    public function programmeAction()
    {
        return $this->render('NAOVisiteursBundle:Default:programme.html.twig');
    }
    
    /**
     * @Route("/recherche-d-oiseau", name="nao_recherche")
     */
    public function rechercheAction()
    {
        return $this->render('NAOVisiteursBundle:Default:recherche.html.twig');
    }
}
