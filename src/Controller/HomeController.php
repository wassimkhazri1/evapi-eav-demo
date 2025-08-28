<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;

final class HomeController extends AbstractController
{

 #[Route('/', name: 'app_home')]
public function home(EntityManagerInterface $em): Response
{
    return $this->render('home/index.html.twig', [
        'projects' => $em->getRepository(Project::class)->findAll()
    ]);
}   

// #[Route('/home', name: 'app_home')]
// public function home1(EntityManagerInterface $em): Response
// {
//     $projects = $em->getRepository(Project::class)->findAll();
//     return $this->render('home/index.html.twig', [
//         'projects' => $projects
//     ]);
// }

// src/Controller/ProjectController.php
#[Route('/project/edit/{id}', name: 'app_project_edit')]
public function edit(
    Request $request,
    Project $project,
    EntityManagerInterface $em
): Response {
    $form = $this->createForm(ProjectType::class, $project);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        $this->addFlash('success', 'Projet mis à jour avec succès');
        return $this->redirectToRoute('app_home');
    }

    return $this->render('project/edit.html.twig', [
        'project' => $project,
        'form' => $form->createView(),
    ]);
}
}
