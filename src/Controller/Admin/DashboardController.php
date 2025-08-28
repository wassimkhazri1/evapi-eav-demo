<?php
namespace App\Controller\Admin;

use App\Entity\Project;
use App\Entity\ProjectValue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// src/Controller/Admin/DashboardController.php
#[Route('/admin', name: 'admin_')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(EntityManagerInterface $em): Response
    {
        // Statistiques pour PRO RÉNOV
        $projects = $em->getRepository(Project::class)->findAll();
        $seoKeywords = $em->getRepository(ProjectValue::class)->findTopAttributes(10);

        return $this->render('admin/dashboard.html.twig', [
            'projects' => $projects,
            'seo_keywords' => $seoKeywords,
            'performance_stats' => [
                'lead_increase' => '42%',  // Exemple concret
                'seo_traffic' => '+67%'
            ]
        ]);
    }
}