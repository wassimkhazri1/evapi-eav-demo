<?php
// src/Form/ProjectValueType.php
namespace App\Form;

use App\Entity\ProjectValue;
use App\Entity\ProjectAttribute; // Utilisez ProjectAttribute au lieu d'Attribute
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface; // Assurez-vous que FormInterface est importé


class ProjectValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('projectAttribute', EntityType::class, [
                'class' => ProjectAttribute::class, // Changé ici
                'choice_label' => 'name',
                'label' => 'Attribut',
                'placeholder' => 'Choisir un attribut',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('value', TextType::class, [
                'label' => 'Valeur',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez la valeur'
                ],
                'empty_data' => '', // ← Garantit une valeur par défaut
                'required' => false // Si tu veux autoriser le vide
            ]);
    }
     public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectValue::class,
            
            // ✅ AJOUTEZ L'OPTION empty_data ICI
            'empty_data' => function (FormInterface $form) {
                
                // Si l'attribut (EntityType) ET la valeur (TextType) sont vides, 
                // on retourne null pour que le formulaire soit ignoré et non persisté.
                if (empty($form->get('projectAttribute')->getData()) && empty($form->get('value')->getData())) {
                    return null;
                }
                
                // Sinon, on retourne une nouvelle instance de l'entité attendue.
                return new ProjectValue();
            },
        ]);
    }

    // public function configureOptions(OptionsResolver $resolver)
    // {
    //     $resolver->setDefaults([
    //         'data_class' => ProjectValue::class,
    //     ]);
    // }
}