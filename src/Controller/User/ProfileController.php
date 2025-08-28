<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProfileController extends AbstractController
{
    #[Route('/user/profile', name: 'app_user_profile')]
    public function index(): Response
    {
        return $this->render('user/profile/index.html.twig', [
            'controller_name' => 'User/ProfileController',
        ]);
    }

        #[Route('/user/settings', name: 'app_user_settings')]
    public function index1(): Response
    {
        return $this->render('user/profile/index.html.twig', [
            'controller_name' => 'User/ProfileController',
        ]);
    }

    
}
