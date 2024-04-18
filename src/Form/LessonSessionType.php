<?php

namespace App\Form;

use App\Entity\ClassRooms;
use App\Entity\LessonSessions;
use App\Entity\LessonTypes;
use App\Entity\Topics;
use App\Entity\TopicsTrainings;
use App\Entity\Users;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LessonSessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('day', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'html5' => false
            ])
            ->add('startHour', TimeType::class,[
                'required' => true,
                'widget' => 'single_text',
                'html5' => false
            ])
            ->add('endHour', TimeType::class,[
                'required' => true,
                'widget' => 'single_text',
                'html5' => false
            ])
            ->add('length', IntegerType::class, [
                'required'=> true
            ])
            ->add('topic', EntityType::class,
                [
                    'class' => TopicsTrainings::class,
                    'query_builder' => function (EntityRepository $er) use ($options): QueryBuilder {
                            return $er->createQueryBuilder('u')
                                ->innerJoin('u.trainings', 't', 'WITH', 't.id = :training')
                                ->setParameter('training', $options['training']->getId())
                                ;
                        },
                    'autocomplete' => true,
                    'required' => true,
                    'tom_select_options' => [
                        'plugins' => [
                                'clear_button' => [
                                    'className' => 'd-none',
                                ]
                            ]
                    ]
                ]
            )
            ->add('teacher', EntityType::class,
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
                ]
            )
            ->add('title', TextType::class,
                [
                    'required' => false,
                ]
            )
            ->add('lessonType', EntityType::class,
                [
                    'class' => LessonTypes::class,
                    'required' => false,
                    //'placeholder' => 'Aucun',
                    'query_builder' => function (EntityRepository $er): QueryBuilder {
                        return $er->createQueryBuilder('u')
                            ->where('u.inactive IS NULL')
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
                ]
            )
            ->add('classRooms', EntityType::class,
                [
                    'class' => ClassRooms::class,
                    'required' => false,
                    'placeholder' => 'Aucun',
                    'query_builder' => function (EntityRepository $er) use ($options) : QueryBuilder {
                        return $er->createQueryBuilder('u')
                            ->innerJoin('u.place', 'p', 'WITH', 'p.id = :place')
                            ->setParameter('place', $options['training']->getPlace()->getId())
                            ->where('u.inactive IS NULL')
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
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LessonSessions::class,
            'training' => false
        ]);
    }
}
