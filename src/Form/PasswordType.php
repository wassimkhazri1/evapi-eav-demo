<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType as SymfonyPasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class PasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', SymfonyPasswordType::class, [
            'attr' => [
                'autocomplete' => 'new-password',
                'class' => 'form-control',
                'placeholder' => 'Enter your password'
            ],
            'label' => 'Password',
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
            'toggle' => $options['toggle'] ?? false, // Custom option example
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'toggle' => false, // Default value for custom option
        ]);
    }

    // Important: This makes your type reusable in other forms
    public function getBlockPrefix(): string
    {
        return 'app_password';
    }
}