<?php

namespace App\Form;

use App\Entity\User;
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




class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [ 'placeholder' => 'Enter your first and last name'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a name',
                    ])
                ],
            ])
            // ->add('name', TextType::class)
            ->add('email', EmailType::class,  [
                'constraints' => [
                    // new Email(['message' => 'Please enter a valid email address.']),
                    new NotBlank([
                        'message' => 'Please enter a email',
                    ]),

                    // new UniqueEntity(['fields' => 'email','message' => 'Email already exists in database!']),
                    
                ],
                'attr' => ['placeholder' => 'Enter email'],
            ])
            // ->add('agreeTerms', CheckboxType::class, [
            //     'mapped' => false,
            //     'constraints' => [
            //         new IsTrue([
            //             'message' => 'You should agree to our terms.',
            //         ]),
            //     ],
            // ])
            ->add('plainPassword', RepeatedType::class, array(
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => array('label' => 'Password', 'attr' => ['placeholder' => 'Enter password']),
                'second_options' => array('label' => 'Repeat Password', 'attr' => ['placeholder' => 'Type again password']),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ));

            //             $form->get('agreeTerms')->getData();
            // $form->get('agreeTerms')->setData(true);


        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
