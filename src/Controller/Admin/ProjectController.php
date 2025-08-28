<?php
// src/Controller/Admin/ProjectController.php
namespace App\Controller\Admin;

use App\Entity\Project;
use App\Entity\ProjectAttribute;
use App\Form\ProjectType;
use App\Form\FeedbackFormType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Psr\Log\LoggerInterface;
use App\Service\ProjectService;
use App\Entity\ProjectValue;

use Knp\Component\Pager\PaginatorInterface;

#[Route('/admin/project')]
#[IsGranted('ROLE_ADMIN')]
class ProjectController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
        private ProjectService $projectService
    ) {
    }

    #[Route('/admin/list', name: 'admin_projects', methods: ['GET'])]
    public function index( ProjectRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {

        $query = $repository->createQueryBuilder('p')
        ->leftJoin('p.user', 'u')
        ->addSelect('u')
        ->getQuery();

        $pagination = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
        10
    );
        return $this->render('admin/project/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }


    #[Route('/project/{id}', name: 'project_show')]
    public function show(Project $project, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création du formulaire de feedback
        $feedbackForm = $this->createForm(FeedbackFormType::class);
        
        // Traitement du formulaire
        $feedbackForm->handleRequest($request);
        
        if ($feedbackForm->isSubmitted() && $feedbackForm->isValid()) {
            $data = $feedbackForm->getData();
            $project->setFeedback($data['comment']);
            
            $entityManager->persist($project);
            $entityManager->flush();
            
            $this->addFlash('success', 'Merci pour votre feedback !');
            
            return $this->redirectToRoute('project_show', ['id' => $project->getId()]);
        }
        
        return $this->render('admin/project/show.html.twig', [
            'project' => $project,
            'feedbackForm' => $feedbackForm->createView(),
        ]);
    }

#[Route('/new', name: 'admin_project_new', methods: ['GET', 'POST'])]
public function new(Request $request): Response
{
    $project = new Project();
    
    // Ajouter une valeur vide par défaut
    $projectValue = new ProjectValue();
    $project->addProjectValue($projectValue);
    
    $form = $this->createForm(ProjectType::class, $project);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
        if ($form->isValid()) {
            try {
                $this->entityManager->persist($project);
                $this->entityManager->flush();
                
                $this->addFlash('success', 'Projet créé avec succès !');
                return $this->redirectToRoute('admin_projects');
            } catch (\Exception $e) {
                $this->logger->error('Project creation failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $this->addFlash('error', 'Erreur lors de la création: ' . $e->getMessage());
            }
        } else {
            // Afficher les erreurs de validation
            foreach ($form->getErrors(true) as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }
    }

    return $this->render('admin/project/new.html.twig', [
        'form' => $form->createView()
    ]);
}   

#[Route('/attribute/create', name: 'attribute_ajax_create', methods: ['POST'])]
public function createAttribute(Request $request): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    if (empty($data['name'])) {
        return $this->json(['error' => 'Le nom est requis'], 400);
    }

    if (empty($data['type'])) {
        return $this->json(['error' => 'Le type est requis'], 400);
    }

    $attribute = new ProjectAttribute();
    $attribute->setName($data['name']);
    $attribute->setType($data['type']);

    try {
        $this->entityManager->persist($attribute);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'attribute' => [
                'id' => $attribute->getId(),
                'name' => $attribute->getName(),
                'type' => $attribute->getType()
            ]
        ]);
    } catch (\Exception $e) {
        return $this->json(['error' => 'Erreur serveur'], 500);
    }
}

    #[Route('/project/{id}/status/{status}', name: 'project_update_status', methods: ['POST'])]
    public function updateStatus(Project $project, string $status): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        try {
            $this->projectService->updateStatus($project, $status);
            $this->addFlash('success', 'Le statut du projet a été mis à jour');
        } catch (\InvalidArgumentException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('project_show', ['id' => $project->getId()]);
    }
    

#[Route('/{id}/edit', name: 'admin_project_edit', methods: ['GET', 'POST'])]
public function edit(Project $project, Request $request): Response
{
     

    $form = $this->createForm(ProjectType::class, $project, [
        'edit_mode' => true
    ]);

    $form->handleRequest($request);
    

    if ($form->isSubmitted() && $form->isValid()) {
        $project->updateTimestamp();
        $entityManager->persist($project); // Make sure Doctrine knows about the change
        $this->entityManager->flush();
        
        $this->addFlash('success', 'Projet mis à jour avec succès !');
        return $this->redirectToRoute('admin_projects');
    }

    return $this->render('admin/project/edit.html.twig', [
        'form' => $form->createView(),
        'project' => $project,
        'edit_mode' => true
    ]);
}
#[Route('/{id}', name: 'admin_project_delete', methods: ['POST'])]
public function delete(Request $request, Project $project): Response
{
    if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
        $this->entityManager->remove($project);
        $this->entityManager->flush();
        $this->addFlash('success', 'Projet supprimé avec succès !');
    }

    return $this->redirectToRoute('admin_projects');
}

    // ... autres méthodes (show, edit, delete)
}