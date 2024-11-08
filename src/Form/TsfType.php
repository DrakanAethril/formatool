<?php

namespace App\Form;

use App\Entity\Skills;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TsfType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('skills', EntityType::class,
                [
                    'class' => Skills::class,
                    'required' => false,
                    'placeholder' => 'Aucun',
                    'choice_label' => 'name',
                    'query_builder' => function (EntityRepository $er) use ($options): ORMQueryBuilder {
                        return $er->createQueryBuilder('s')
                            ->innerJoin('s.topics_group', 'tg')
                            ->innerJoin('tg.cursus', 'c')
                            ->where(':training MEMBER OF c.trainings')
                            ->setParameter('training', $options['training']->getId())
                        ;
                    },
                    'autocomplete' => true,
                    'multiple' => true,
                    'tom_select_options' => [
                        'plugins' => [
                            'clear_button' => [
                                    'className' => 'd-none',
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
            // Configure your form options here
            'training' => false
        ]);
    }
}
