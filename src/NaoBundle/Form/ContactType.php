<?php

namespace NaoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;


class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Nom :'
            ))
            ->add('firstname', TextType::class, array(
                'label' => 'Prénom :'
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Adresse e-mail :'
            ))
            ->add('object', TextType::class, array(
                'label' => 'Objet :'
            ))
            ->add('message', TextareaType::class, array(
                'label' => 'Votre message :'
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NaoBundle\Entity\Contact'
        ));
    }

}