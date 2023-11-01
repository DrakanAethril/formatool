<?php

namespace App\Form;

use App\Entity\TimeSlots;
use App\Entity\TimeSlotsTypes;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimeSlotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('start_date', DateTimeType::class, [
                'required' => true,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy HH:mm:59',
                'html5' => false
            ])
            ->add('end_date', DateTimeType::class, [
                'required' => true, 
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy HH:mm:59',
                'html5' => false
            ])
            ->add('timeSlotsTypes', EntityType::class,
            [
                'class' => TimeSlotsTypes::class,
                'required' => true,
                'tom_select_options' => [
                    'plugins' => [
                            'clear_button' => [
                                'className' => 'd-none',
                            ]
                        ]
                ],
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'autocomplete' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TimeSlots::class,
        ]);
    }
}
