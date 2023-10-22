<?php

namespace App\Form;

use App\Entity\Topics;
use App\Entity\TopicsGroups;
use App\Entity\TopicsTrainings;
use App\Entity\TopicsTrainingsLabel;
use App\Entity\Trainings;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TopicsTrainingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('topics', EntityType::class,
                    [
                        'class' => Topics::class,
                       'query_builder' => function (EntityRepository $er): QueryBuilder {
                            return $er->createQueryBuilder('u')
                                ->orderBy('u.name', 'ASC');
                        },
                    ]
                )
            ->add('topicsTrainingsLabels', EntityType::class,
                [
                    'class' => TopicsTrainingsLabel::class,
                    'multiple' => true,
                    'by_reference' => false,
                    'required' => false,
                    'query_builder' => function (EntityRepository $er): QueryBuilder {
                        return $er->createQueryBuilder('u')
                            ->orderBy('u.name', 'ASC');
                    },
                ]
            )
            ->add('topicsGroups', EntityType::class,
                    [
                        'class' => TopicsGroups::class,
                        'required' => false,
                        'placeholder' => 'Aucune',
                        'query_builder' => function (EntityRepository $er): QueryBuilder {
                            return $er->createQueryBuilder('u')
                                ->orderBy('u.name', 'ASC');
                        },
                    ]
                )   
            ->add('cm', IntegerType::class)
            ->add('td', IntegerType::class)
            ->add('tp', IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TopicsTrainings::class,
        ]);
    }
}
