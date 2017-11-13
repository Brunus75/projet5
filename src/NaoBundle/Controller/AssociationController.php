<?php

namespace NaoBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


/**
 * Class AssociationController
 * @package NaoBundle\Controller
 */
class AssociationController extends Controller
{
    /**
     * Ce que l'on peut faire sur la page Association
     * @route("/association", name="associationPage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function associationAction(Request $request)
    {
        return $this->render('NaoBundle:Front:association.html.twig');
    }
}
