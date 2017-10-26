<?php

namespace NAOMembresBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class UserType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('enabled',CheckboxType::class, array("required"=>false))
            ->add('password')
            ->add('roles',ChoiceType::class,array(
                                'choices'=>array(
                                    'UTILISATEUR'=>"ROLE_USER",
                                    'ORNITHOLOGUE'=>'ROLE_ORNITHOLOGUE',
                                    "NATURALISTE"=>"ROLE_NATURALISTE", 
                                    "ADMINISTRATEUR"=>"ROLE_ADMINISTRATEUR",
                                    ),
                                'multiple' => true,
                                'expanded' => true,
                ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\AdminBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms_adminbundle_user';
    }
}
