<?php

namespace App\Form;

use App\Config\UsersRolesTrainingsEnum;
use App\Config\UsersStatusTrainingsEnum;
use App\Entity\Trainings;
use App\Entity\TrainingsOptions;
use App\Entity\Users;
use App\Entity\UsersTrainings;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsersTrainingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('statusEntry', EnumType::class, [
                'class' => UsersStatusTrainingsEnum::class,
                'mapped' => false,
                'autocomplete' => true,
                'required' => true,
                'data' => $options['status'],
                'tom_select_options' => [
                    'plugins' => [
                            'clear_button' => [
                                'className' => 'd-none',
                            ]
                        ]
                ]
            ])
            ->add('rolesEntry', EnumType::class, [
                'class' => UsersRolesTrainingsEnum::class,
                'mapped' => false,
                'autocomplete' => true,
                'data' => $options['roles'],
                'required' => true,
                'multiple' => true,
                'tom_select_options' => [
                    'plugins' => [
                            'clear_button' => [
                                'className' => 'd-none',
                            ]
                        ]
                ]
            ])
            ->add('user', EntityType::class,
                [
                    'class' => Users::class,
                    'required' => false,
                    'placeholder' => 'Aucun',
                    'disabled' => !$options['create'],
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
                ]
            )
        ;
        if(!empty($options['training']->getTrainingsOptions())) {
            $builder->add('trainingOptions', EntityType::class,
                [
                    'class' => TrainingsOptions::class,
                    'multiple' => true,
                    'by_reference' => false,
                    'required' => true,
                    'query_builder' => function (EntityRepository $er) use ($options): QueryBuilder {
                        return $er->createQueryBuilder('o')
                            ->innerJoin('o.training', 'tr', 'WITH', 'tr.id = :training')
                            ->setParameter('training', $options['training']->getId())
                            ->orderBy('o.shortname', 'ASC');
                    },
                    'autocomplete' => true
                ]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UsersTrainings::class,
            'status' => false,
            'roles' => false,
            'create' => true,
            'training' => false
        ]);
    }
}
