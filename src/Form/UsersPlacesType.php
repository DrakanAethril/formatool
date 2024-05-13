<?php

namespace App\Form;

use App\Config\UsersRolesPlacesEnum;
use App\Config\UsersStatusPlacesEnum;
use App\Entity\Places;
use App\Entity\Users;
use App\Entity\UsersPlaces;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;



class UsersPlacesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('statusEntry', EnumType::class, [
                'class' => UsersStatusPlacesEnum::class,
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
                'class' => UsersRolesPlacesEnum::class,
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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UsersPlaces::class,
            'status' => false,
            'roles' => false,
            'create' => true
        ]);
    }
}
