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
        ]);
    }
}
