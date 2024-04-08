<?php

namespace App\Form;

use App\Config\FinancialItemsSourceEnum;
use App\Config\FinancialItemsTypeEnum;
use App\Entity\LessonTypes;
use App\Entity\TrainingFinancialItems;
use Exception;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TextType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\CallbackTransformer as CallbackTransformer;

use function PHPUnit\Framework\throwException;

class FinancialItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                    'required' => true,
                ]
            )
            ->add('description', CKEditorType::class, [
                'required'=> false,
            ])
            ->add('typeEntry', EnumType::class, [
                'class' => FinancialItemsTypeEnum::class,
                'mapped' => false,
                'data' => $options['typeEntry'],
                'autocomplete' => true,
                'required' => true,
                'tom_select_options' => [
                    'plugins' => [
                            'clear_button' => [
                                'className' => 'd-none',
                            ]
                        ]
                ]
            ])
            ->add('value', NumberType::class, [
                'required' => true,
                'html5' => false
            ])
            ->add('sourceEntry', EnumType::class, [
                'class' => FinancialItemsSourceEnum::class,
                'mapped' => false,
                'disabled' => true,
                'autocomplete' => true,
                'data' => $options['sourceEntry'],
                'tom_select_options' => [
                    'plugins' => [
                            'clear_button' => [
                                'className' => 'd-none',
                            ]
                        ]
                ]
            ]);

            switch($options['source']) {
                case FinancialItemsSourceEnum::SourceLesson:
                    $builder
                        ->add('lessonType', EntityType::class, [
                            'class' => LessonTypes::class,
                            'query_builder' => function (EntityRepository $er): QueryBuilder {
                                return $er->createQueryBuilder('u')
                                    ->orderBy('u.name', 'ASC');
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
                        ]);  
                    break;
                case FinancialItemsSourceEnum::SourceStudent:

                    break;
                case FinancialItemsSourceEnum::SourceManual:
                    $builder
                        ->add('quantity', NumberType::class, [
                            'required' => true,
                            'html5' => false
                        ]);
                    break;
                default:
                    throw(new Exception("undefined source"));
            }
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrainingFinancialItems::class,
            'source' => false,
            'sourceEntry' => false,
            'typeEntry' => false
        ]);
    }
}
