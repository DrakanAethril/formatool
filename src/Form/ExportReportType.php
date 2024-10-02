<?php

namespace App\Form;

use App\Entity\TrainingsOptions;
use App\Entity\Users;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExportReportType extends AbstractType
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
            ->add('title', TextType::class, [
                'required' => true,
            ])
            ->add('description', CKEditorType::class, [
                'required'=> true,
            ])
            ->add('referee', EntityType::class,
                [
                    'class' => Users::class,
                    'required' => true,
                    'placeholder' => 'Aucun',
                    'query_builder' => function (EntityRepository $er) use ($options): QueryBuilder {
                        return $er->createQueryBuilder('u')
                            ->innerJoin('u.usersTrainings', 'utr', 'WITH', 'utr.training = :training')
                            ->setParameter('training', $options['training']->getId())
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
            // Configure your form options here
            'training' => false
        ]);
    }
}
