<?php

namespace App\Form;

use App\Entity\Trainings;
use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['autocomplete' => 'email', 'class' => 'form-control'],
                'label_attr' => ['class' => 'form-label required'],   
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mail est obligatoire',
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'attr' => ['autocomplete' => 'firstname', 'class' => 'form-control'],
                'label' => 'Prénom',
                'label_attr' => ['class' => 'form-label required'],   
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le prénom est obligatoire',
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'attr' => ['autocomplete' => 'lastname', 'class' => 'form-control pt-3'],
                'label' => 'Nom',
                'label_attr' => ['class' => 'form-label required'],   
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom est obligatoire',
                    ]),
                ],
            ])
            ->add('training', EntityType::class, [
                'choice_label' => 'shortTitle',
                'attr' => ['class' => 'form-select'],
                'label' => 'Formation',
                'label_attr' => ['class' => 'form-label required'],   
                'mapped' => false,
                'required' => true,
                'class' => Trainings::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('t')
                        ->where('t.inactive IS NULL')
                        ->andWhere('t.startTrainingDate > :now')
                        ->setParameter('now', new \DateTime('now'))
                        ->orderBy('t.shortTitle', 'ASC');
                }
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'reques_reset',
                //'script_nonce_csp' => $nonceCSP,
                'locale' => 'fr',
            ])
            /*->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos termes.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation du mot de passe'],
                'constraints' => UsersRepository::getPasswordContstraints(),
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => 'csrf_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => '_pre_register_',
        ]);
    }
}
