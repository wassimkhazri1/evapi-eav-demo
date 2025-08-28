<?php

namespace App\DataFixtures;
use App\Entity\ProjectAttribute;
use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
// Crée des attributs PRO RÉNOV
// php bin/console doctrine:fixtures:load
        $attributes = [
            ['name' => 'Budget', 'type' => 'number'],
            ['name' => 'Délai (jours)', 'type' => 'number'],
            ['name' => 'Éco-friendly', 'type' => 'boolean']
        ];

        foreach ($attributes as $data) {
            $attr = new ProjectAttribute();
            $attr->setName($data['name'])->setType($data['type']);
            $manager->persist($attr);
        }

        // Crée un projet exemple
        $project = new Project();
        $project->setName('Rénovation complète')->setClient('PRO RÉNOV');
        $manager->persist($project);

        $manager->flush();
    }
}

// <?php

// namespace App\DataFixtures;

// use App\Entity\Attribute;
// use Doctrine\Bundle\FixturesBundle\Fixture;
// use Doctrine\Persistence\ObjectManager;

// class AppFixtures extends Fixture
// {
//     public function load(ObjectManager $manager): void
//     {
//         // Création des attributs de base
//         $attributes = [
//             ['color', 'Couleur', 'string'],
//             ['size', 'Taille', 'string'],
//             ['weight', 'Poids (kg)', 'decimal'],
//             ['description', 'Description', 'text'],
//             ['is_available', 'Disponible', 'boolean'],
//             ['manufacture_date', 'Date de fabrication', 'date'],
//         ];

//         foreach ($attributes as [$code, $label, $type]) {
//             $attribute = new Attribute();
//             $attribute->setCode($code);
//             $attribute->setLabel($label);
//             $attribute->setType($type);
//             $manager->persist($attribute);
//         }

//         $manager->flush();
//     }
// }