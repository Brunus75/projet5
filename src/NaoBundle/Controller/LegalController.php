<?php

namespace NaoBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;



/**
 * Class LegalController
 * @package NaoBundle\Controller
 */
class LegalController extends Controller
{
    /**
     * Ce que l'on peut faire sur la page Légale
     * @route("/legal", name="legalPage")
     */
    public function legalAction()
    {
        return $this->render('NaoBundle:Front:legal.html.twig', [

        ]);
    }
}
