<?php

namespace App\Controller\User;

use App\Controller\SecurityController;
use App\Entity\Project;
use App\Entity\ProjectAttribute;
use App\Entity\User;
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
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\ProjectValue;

#[Route('/user/project')]
#[IsGranted('ROLE_USER')]
class ProjectController extends AbstractController
{

        public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
        private ProjectService $projectService
    ) {
    }

#[Route('/list', name: 'user_projects')]
public function index(
    ProjectRepository $repository, 
    PaginatorInterface $paginator, 
    Request $request,
    SecurityController $security,
): Response
{
    $user = $security->getUser();
    
    // Récupérer le nombre d'éléments par page depuis la requête ou utiliser 10 par défaut
    $itemsPerPage = $request->query->getInt('itemsPerPage', 10);
    
    $pagination = $repository->findByUserPaginated(
        $user, 
        $paginator, 
        $request->query->getInt('page', 1),
        $itemsPerPage
    );
    
    return $this->render('user/project/index.html.twig', [
        'pagination' => $pagination,
        'itemsPerPage' => $itemsPerPage,
    ]);
}

#[Route('/nouveau', name: 'user_project_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $project = new Project();
    
    $form = $this->createForm(ProjectType::class, $project);
    $form->handleRequest($request);

    // if ($form->isSubmitted() && $form->isValid()) { 
        if ($form->isSubmitted()) {
        
        // 🛑 LIGNE DE DÉBOGAGE À AJOUTER TEMPORAIREMENT 🛑
        if (!$form->isValid()) {
            dd($form->getErrors(true)); 
        }

        if ($form->isValid()) {
            try {

            if (!$this->isGranted('ROLE_ADMIN')) {
                $project->setUser($this->getUser());
            }


                $this->entityManager->persist($project);
                $this->entityManager->flush();
                
                $this->addFlash('success', 'Projet créé avec succès !');
                return $this->redirectToRoute('user_projects');
            } catch (\Exception $e) {
                $this->logger->error('Project creation failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $this->addFlash('error', 'Erreur lors de la création: ' . $e->getMessage());
            }
        } else {
            foreach ($form->getErrors(true) as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }
    }
    return $this->render('user/project/new.html.twig', [
        'project' => $project,
        'form' => $form->createView()
    ]);
}


#[Route('/new', name: 'user1_project_new', methods: ['GET', 'POST'])]
public function new1(Request $request): Response
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
                $project->setUser($this->getUser());
                $this->entityManager->persist($project);
                $this->entityManager->flush();
                
                $this->addFlash('success', 'Projet créé avec succès !');
                return $this->redirectToRoute('user_projects');
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

    return $this->render('user/project/new.html.twig', [
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

    #[Route('/{id}', name: 'user_project_show', requirements: ['id' => '\d+'])]
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
            
            return $this->redirectToRoute('user_project_show', ['id' => $project->getId()]);
        }

        return $this->render('user/project/show.html.twig', [
            'project' => $project,
            'feedbackForm' => $feedbackForm->createView(),
        ]);
    }
    #[Route('/{id}/edit', name: 'user_project_edit', methods: ['GET', 'POST'])]
    public function edit(Project $project, Request $request, EntityManagerInterface $entityManager): Response
    {
        
        
        $form = $this->createForm(ProjectType::class, $project, [
            'edit_mode' => true
        ]);

        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $project->updateTimestamp();
            $entityManager->persist($project);
            $this->entityManager->flush();
            
            $this->addFlash('success', 'Projet mis à jour avec succès !');
            return $this->redirectToRoute('user_projects');
        }

        return $this->render('user/project/edit.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
            'edit_mode' => true
        ]);
    }

}