<?php

namespace NaoBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use NaoBundle\Entity\Observation;


class ObservationType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            ->add('date', DateTimeType::class,
                [
                    'label'=> 'Date d\'observation',
                    'view_timezone' => 'Europe/Paris',
                    'date_widget' => 'single_text', 'time_widget' => "single_text",
                    'data' => new \DateTime('now')
                ]
            )

            ->add('oiseau', AutocompleteType::class, array(
                'class' => 'NaoBundle:Especes',
                'required' => true,
                'label'=> false,
                'attr'=> array(
                    'placeholder' => 'Nom de l\'oiseau',
                    )
            ))

            ->add('description', TextareaType::class, array(
                'label' => 'Description (facultatif)',
                'required' => false,
                'invalid_message' => 'Champ obligatoire, 250 caractères max.'

            ))

            ->add('image', ImageType::class, array(
                'label' => false,
                'required' => false,
            ))


            ->add('latitude', NumberType::class, array(
                'label' => 'Coordonnées GPS: Latitude',
                'invalid_message' => 'Caractères numériques uniquement',
                'scale' => 10,
                'attr' => array(
                    'onchange' => 'codeLatLng()'
                )
            ))


            ->add('longitude', NumberType::class, array(
                'label' => 'Longitude',
                'invalid_message' => 'Caractères numériques uniquement',
                'scale' => 10,
                'attr' => array(
                    'onchange' => 'codeLatLng()'
                )
            ))

            ->add('ville', TextareaType::class, array(
                'label' => 'Ville',
                'required' => false,
                'invalid_message' => 'Champ obligatoire,  caractères 30 max.'
            ))

            ->add('save', SubmitType::class, array(
                'label' => 'Valider',
                'attr'=> array(
                    'class' => 'btnSubmit',
                )

            ))
        ;


    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Observation::class,
        ]);
    }
}