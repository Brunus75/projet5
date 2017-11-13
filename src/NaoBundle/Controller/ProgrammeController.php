<?php

namespace NaoBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;



/**
 * Class ProgrammeController
 * @package NaoBundle\Controller
 */
class ProgrammeController extends Controller
{
    /**
     * Ce que l'on peut faire sur la page Programme
     * @route("/programme", name="programmePage")
     */
    public function programmeAction()
    {
        return $this->render('NaoBundle:Front:programme.html.twig');
    }
}
