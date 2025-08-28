<?php
// src/DataFixtures/ProjectAttributeFixtures.php
namespace App\DataFixtures;

use App\Entity\ProjectAttribute;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProjectAttributeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $attributes = ['Surface', 'Couleur', 'Style', 'Budget', 'Durée'];
        
        foreach ($attributes as $name) {
            $attribute = new ProjectAttribute();
            $attribute->setName($name);
            $manager->persist($attribute);
            $this->addReference('attr_'.$name, $attribute);
        }

        $manager->flush();
    }
}