<?php
// src/Form/ProjectType.php
namespace App\Form;

use App\Entity\Project;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\ProjectValueType;
use Symfony\Component\Form\FormFactoryInterface;
use Twig\Environment;

class ProjectType0 extends AbstractType
{
    private $formFactory;
    private $twig;

    public function __construct(FormFactoryInterface $formFactory, Environment $twig)
    {
        $this->formFactory = $formFactory;
        $this->twig = $twig;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Configuration du champ status
        $statusOptions = [
            'label' => 'Statut',
            'choices' => [
                'En cours' => Project::STATUS_ACTIVE,
                'Terminé' => Project::STATUS_COMPLETED,
                'Archivé' => Project::STATUS_ARCHIVED,
            ],
            'placeholder' => 'Sélectionnez un statut',
            'attr' => ['class' => 'form-select'],
            'label_attr' => ['class' => 'form-label required'],
        ];

        if ($options['edit_mode']) {
            $statusOptions['disabled'] = true;
        }

        $builder
            ->add('name', TextType::class, [
                'label' => 'project.form.name',
                'attr' => ['class' => 'form-control']
            ])
            ->add('status', ChoiceType::class, $statusOptions)
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => fn(User $user) => sprintf('%s (%s)', $user->getEmail(), $user->getFirstName()),
                // 'query_builder' => fn(UserRepository $er) => $er->createQueryBuilder('u')
                //     ->where('u.roles LIKE :role')
                //     ->setParameter('role', '%ROLE_USER%'),
                'placeholder' => 'Sélectionnez un client',
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group']
            ])
            ->add('projectValues', CollectionType::class, [
                'entry_type' => ProjectValueType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'prototype_name' => '__project_value_prototype__',
                'label' => false,
                'attr' => [
                    'class' => 'values-collection',

                ]
            ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
            'edit_mode' => false,
        ]);
    }
}