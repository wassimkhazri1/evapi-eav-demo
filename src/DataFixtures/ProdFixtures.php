<?php
// src/DataFixtures/ProdFixtures.php
namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\ProjectValue;
use App\Entity\ProjectAttribute;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProdFixtures implements FixtureInterface, DependentFixtureInterface
{
    public function load(ObjectManager $manager): void  // Ajoutez ": void" ici
    {
        // Client PRO RÉNOV
        $proRenov = (new Project())
            ->setName('Rénovation Cuisine')
            ->setClient('PRO RÉNOV');

        $attributes = [
            'Budget' => '25 000€',
            'Délai' => '3 semaines',
            'Matériaux' => 'Bois certifié'
        ];

        foreach ($attributes as $name => $value) {
            $attr = $manager->getRepository(ProjectAttribute::class)
                           ->findOneBy(['name' => $name]);
            
            if ($attr) {
                $proRenov->addValue(
                    (new ProjectValue())
                        ->setAttribute($attr)
                        ->setValue($value)
                );
            }
        }

        $manager->persist($proRenov);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProjectAttributeFixtures::class
        ];
    }
}