<?php

namespace App\Form;

use App\Entity\ClassRooms;
use App\Entity\Trainings;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class ClassRoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('trainings', EntityType::class, [
                'class' => Trainings::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.title', 'ASC');
                },
                'autocomplete' => true,
                'multiple' => true,
                'required' => false,
                'tom_select_options' => [
                    'plugins' => [
                            'clear_button' => [
                                'className' => 'd-none',
                            ]
                        ]
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClassRooms::class,
        ]);
    }
}
