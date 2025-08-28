<?php
// src/DataFixtures/UserFixtures.php
namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Project;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // 1. Créez le projet
        $project = new Project();
        $project->setName('Rénovation Cuisine');
        $project->setClient('PRO RÉNOV');
        $manager->persist($project);

        // 2. Créez l'utilisateur
        $user = new User();
        $user->setEmail('client@example.com');
        $user->setPassword($this->hasher->hashPassword($user, '123456'));
        $user->setRoles(['ROLE_CLIENT']);
        $user->setClientName('PRO RÉNOV');
        $user->setProject($project);

        $manager->persist($user);
        $manager->flush();
    }
}