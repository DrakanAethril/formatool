<?php

namespace App\Form;

use App\Entity\Cursus;
use App\Entity\Trainings;
use App\Entity\TrainingsModality;
use App\Entity\Users;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrainingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
            ])
            ->add('shortTitle', TextType::class, [
                'required' => false,
            ])
            ->add('cursus', EntityType::class,
            [
                'class' => Cursus::class,
                'required' => false,
                //'placeholder' => 'Aucun',
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'autocomplete' => true,
                'tom_select_options' => [
                    'plugins' => [
                            'clear_button' => [
                                'className' => 'clear-button icon',
                            ]
                        ]
                ]
            ])
            ->add('trainingsModality', EntityType::class,
            [
                'class' => TrainingsModality::class,
                'required' => false,
                //'placeholder' => 'Aucun',
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'autocomplete' => true,
                'tom_select_options' => [
                    'plugins' => [
                            'clear_button' => [
                                'className' => 'clear-button icon',
                            ]
                        ]
                ]
            ])
            ->add('startTrainingDate', DateTimeType::class, [
                'required' => true,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy HH:ii:ss',
                'html5' => false
            ])
            ->add('endTrainingDate', DateTimeType::class, [
                'required' => true,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy HH:ii:ss',
                'html5' => false
            ])
            ->add('owner', EntityType::class,
            [
                'class' => Users::class,
                'required' => false,
                'placeholder' => 'Aucun',
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.lastname', 'ASC');
                },
                'autocomplete' => true,
                'tom_select_options' => [
                    'plugins' => [
                            'clear_button' => [
                                'className' => 'clear-button icon',
                            ]
                        ]
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trainings::class,
        ]);
    }
}
