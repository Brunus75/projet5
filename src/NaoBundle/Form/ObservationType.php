<?php

namespace NaoBundle\Form;


use NaoBundle\Repository\EspecesRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ObservationType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder


            ->add('date',  DateTimeType::class,
                array(
                    'label'=> 'Date d\'observation',
                    'view_timezone' => 'Europe/Paris',
                    'date_widget' => "single_text", 'time_widget' => "single_text",
                    'data' => new \DateTime('now'))
            )

            ->add('bird', AutocompleteType::class, array(
                'class' => 'NaoBundle:Especes',
                'required' => true,
                'label'=> false,
                'attr'=> array(
                    'placeholder' => 'Nom de l\'oiseau',
                    )
            ))

            ->add('description', TextareaType::class, array(
                'label' => 'Description',
                'required' => false,

            ))

            ->add('picture',     ImageType::class, array(
                'label' => false,
                'required' => false
            ))


            ->add("latitude", NumberType::class, array(
                'label' => 'Latitude'
            ))
            ->add("longitude", NumberType::class, array(
                'label' => 'Longitude'
            ))

            ->add('save', SubmitType::class, array(
                'label' => 'Soumettre',
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
            'data_class' => 'NaoBundle\Entity\Observation',
        ]);
    }
}