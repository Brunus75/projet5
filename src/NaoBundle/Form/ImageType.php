<?php

namespace NaoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, array(
                'label'=>false,
                'required' => false,
                'attr' => array(
                    'accept' => 'image/*',
                    'capture' => 'environment',
                    'onchange' => 'updateFilename(this.value)'
                )))
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
            'data_class' => 'NaoBundle\Entity\Image',
            ]
        );
    }
}