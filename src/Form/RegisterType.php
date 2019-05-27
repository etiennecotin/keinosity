<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('username')
            ->add('email', EmailType::class, [
                'label' => 'form.registration.email',
                'attr' => [
                    'class' => 'form-control input-lg'
                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'form.registration.lastname',
                'attr' => [
                    'class' => 'form-control input-lg'
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => 'form.registration.firstname',
                'attr' => [
                    'class' => 'form-control input-lg'
                ]
            ])
            ->add('birthDay', DateType::class, [
                'label' => 'form.registration.birthday',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd-MM-yyyy',
                'attr' => [
                    'class' => 'form-control input-lg',
                    'data-provide' => 'datepicker'
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'form.registration.password', 'attr' => [
                    'class' => 'form-control input-lg'
                ],],
                'second_options' => ['label' => 'form.registration.repeatedPassword', 'attr' => [
                    'class' => 'form-control input-lg'
                ],],
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('termsAccepted', CheckboxType::class, [
                'mapped' => false,
                'label' => 'form.registration.termAccepted',
                'constraints' => new IsTrue(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
