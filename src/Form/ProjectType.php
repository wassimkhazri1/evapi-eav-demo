<?php
namespace App\Form;

use App\Entity\Project;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security; // Import the Security service

class ProjectType extends AbstractType
{
    private $security;

    // Inject the Security service via constructor
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isAdmin = $this->security->isGranted('ROLE_ADMIN');
        // $currentUser = $this->security->getUser();
        
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du projet',
                'attr' => ['class' => 'form-control'],
                'required' => true
            ]);
            
        // Add status field ONLY for admins
        if ($isAdmin) {
            $builder->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'En cours' => Project::STATUS_ACTIVE,
                    'Terminé' => Project::STATUS_COMPLETED,
                    'Archivé' => Project::STATUS_ARCHIVED,
                ],
                'attr' => ['class' => 'form-select'],
                'required' => true
            ])
                ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => function(User $user) {
                    return $user->getEmail() . ' (' . $user->getFirstName() . ')';
                },
                'placeholder' => 'Sélectionnez un client',
                'attr' => ['class' => 'form-control'],
                'required' => true
            ]);
        }
        //  else {
        //     // Pour les utilisateurs normaux, utiliser HiddenType au lieu de EntityType
        //     $builder->add('user', HiddenType::class, [
        //         'data' => $currentUser->getId(),
        //         'mapped' => false, // Important: ne pas mapper directement à l'entité
        //         'required' => true
        //     ]);
        // }

        $builder
            ->add('projectValues', CollectionType::class, [
                'entry_type' => ProjectValueType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'label' => 'Valeurs du projet',
                'attr' => ['class' => 'project-values-collection']
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