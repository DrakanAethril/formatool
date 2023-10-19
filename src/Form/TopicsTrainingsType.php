<?php

namespace App\Form;

use App\Entity\Topics;
use App\Entity\TopicsTrainings;
use App\Entity\TopicsTrainingsLabel;
use App\Entity\Trainings;
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
                        'class' => Topics::class
                    ]
                )
            ->add('topicsTrainingsLabels', EntityType::class,
                [
                    'class' => TopicsTrainingsLabel::class,
                    'multiple' => true
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
