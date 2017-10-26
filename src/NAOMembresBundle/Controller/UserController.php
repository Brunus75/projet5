<?php

namespace NAOMembresBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use NAOMembresBundle\Entity\User;
use NAOMembresBundle\Form\UserType;
use NAOMembresBundle\Entity\Role;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
//use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\CustomNormalizer;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * User controller.
 *
 */
class UserController extends Controller {

    /**
     * Lists all User entities.
     * @Route("/index", name="nao_membres_index")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        
        $roles = $em->getRepository("NAOMembresBundle:Role")->findOneBy(array('nomEntity'=>"NAOMembresBundle:User"));
        $entities = $em->getRepository('NAOMembresBundle:User')->findAll();

        return $this->render('NAOMembresBundle:User:index.html.twig', array(
                    'entities' => $entities,
                    "roles" => $roles,
                    "rolesRole" => $rolesRole
        ));
    }

    /**
     * Creates a new User entity.
     * @Route("/create", name="nao_membres_create")
     */
    public function createAction(Request $request) {
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($entity, $entity->getPassword());
            echo $entity->getPassword();
            $entity->setPassword($encoded);
            echo var_dump($entity);
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('nao_membres_show', array('id' => $entity->getId())));
        }
//        return $this->render('BLOGVisiteursBundle:Default:vide.html.twig');
        return $this->render('NAOMembresBundle:User:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $entity) {
        $form = $this->createForm(UserType::class, $entity, array(
            'action' => $this->generateUrl('nao_membres_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Créer'));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     * @Route("/new", name="nao_membres_new")
     */
    public function newAction() {
        
        $em = $this->getDoctrine()->getManager();
        
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $roles = $em->getRepository("NAOMembresBundle:Role")->findOneBy(array('nomEntity'=>"NAOMembresBundle:User"));

        return $this->render('NAOMembresBundle:User:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    "roles" => $roles
        ));
    }

    /**
     * Finds and displays a User entity.
     * @Route("/show/{id}", name="nao_membres_show")
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NAOMembresBundle:User')->find($id);
        $roles = $em->getRepository("NAOMembresBundle:Role")->findOneBy(array('nomEntity'=>"NAOMembresBundle:User"));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('NAOMembresBundle:User:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
                    "roles" => $roles
                ));
    }

    /**
     * Displays a form to edit an existing User entity.
     * @Route("/edit/{id}", name="nao_membres_edit")
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NAOMembresBundle:User')->find($id);
        $roles = $em->getRepository("NAOMembresBundle:Role")->findOneBy(array('nomEntity'=>"NAOMembresBundle:User"));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('NAOMembresBundle:User:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    "roles" => $roles
        ));
    }

    /**
     * Creates a form to edit a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(User $entity) {
        $form = $this->createForm(UserType::class, $entity, array(
            'action' => $this->generateUrl('nao_membres_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Modifier'));

        return $form;
    }

    /**
     * Edits an existing User entity.
     * @Route("/update/{id}", name="nao_membres_update")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NAOMembresBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('nao_membres_edit', array('id' => $id)));
        }

        return $this->render('NAOMembresBundle:User:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    "roles" => $roles
        ));
    }

    /**
     * Deletes a User entity.
     * @Route("/delete/{id}", name="nao_membres_delete")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        $roles = $em->getRepository("NAOMembresBundle:Role")->findOneBy(array('nomEntity'=>"NAOMembresBundle:User"));
        
        if (!$this->get('security.authorization_checker')->isGranted($roles->getSuppression())){
            throw new \Exception("Accés refusé");
        }
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('NAOMembresBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('nao_membres_user'));
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('nao_membres_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', SubmitType::class, array('label' => 'Effacer'))
                        ->getForm()
        ;
    }

    /**
     * 
     * @Route("/definition-roles", name="nao_membres_defRoles")
     */
    public function defRolesAction() {
        $em = $this->getDoctrine()->getManager();
        chdir("../src/NAOMembresBundle/Entity");
        $nomDossierEntity = getcwd();
        $tabNomFichier = scandir($nomDossierEntity);


        $nomBundleOrigine = $em->getClassMetadata('NAOMembresBundle:User')->getName();
        $indexTabNomBundle = 0;
        $nomBundle = "";

        $tok = strtok($nomBundleOrigine, "\\");
        $tabNomBundle[$indexTabNomBundle] = $tok;

        if ($tok) {
            $indexTabNomBundle++;
            while ($tok !== false) {
                $tok = strtok("\\");

                $tabNomBundle[$indexTabNomBundle] = $tok;
                $indexTabNomBundle++;
            }
            if (count($tabNomBundle) == 4) {
                $nomBundle = $tabNomBundle[0] . ":";
            }
        }

        $tabBddEntities = array();
        $indexFichier = 0;

        $rec = false;
        $entities = $em->getRepository("NAOMembresBundle:Role")->findAll();
        $tabEntities = array();


        for ($index_entity = 0; $index_entity < count($entities); $index_entity++) {
            $tabBddEntities[$entities[$index_entity]->getNomEntity()] = $entities[$index_entity];
        }

        for ($L_index_fichier = 0; $L_index_fichier < count($tabNomFichier); $L_index_fichier++) {

            $str = "Repository";
            $pos = strpos($tabNomFichier[$L_index_fichier], $str);
            if ($pos != false || $tabNomFichier[$L_index_fichier] == '.' || $tabNomFichier[$L_index_fichier] == '..') {
                $nomFichiers[$indexFichier] = $pos . " - " . $tabNomFichier[$L_index_fichier];
                $indexFichier++;
            } else {
                $nom_class = substr($tabNomFichier[$L_index_fichier], 0, -4);

                $nom_fichier = $nomBundle . $nom_class;
                $class = $em->getClassMetadata($nom_fichier)->getName();
                if (isset($class::$catEntity) && isset($class::$catEntity)) {
                    $categorie = $class::$catEntity;
                    $nomEntity = $class::$nameEntity;
                    if (!isset($tabEntities[$categorie])) {
                        $tabEntities[$categorie] = array();
                    }

                    if (isset($tabBddEntities[$nom_fichier])) {
                        $tabEntities[$categorie][$nomEntity] = $tabBddEntities[$nom_fichier];
                        unset($tabBddEntities[$nom_fichier]);
                    } else {
                        $role = new Role();
                        $role->setNomEntity($nom_fichier);
                        $role->setCreation(1);
                        $role->setModification(1);
                        $role->setSuppression(1);


                        $tabEntities[$categorie][$nomEntity] = $role;
                        
                        $em->persist($role);
                        $rec = true;
                    }

                    $indexFichier++;
                }
            }
        }


        foreach ($tabBddEntities as $key => $value) {
            $em->remove($tabBddEntities[$key]);
            $rec = true;
        }

        if ($rec) {
            $em->flush();
        }

        ksort($tabEntities);
        
        return $this->render('NAOMembresBundle:User:defRoles.html.twig', array("entities" => $tabEntities));
    }

   

}
