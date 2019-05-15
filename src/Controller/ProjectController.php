<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\User;
use App\Form\ProjectFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ProjectController
 * @package App\Controller
 * @Route("/project")
 */
class ProjectController extends AbstractController
{
    /**
     * @Route("/", name="project")
     */
    public function index()
    {
        $projects = $this->getDoctrine()->getRepository(Project::class)->findAll();

        return $this->render('project/index.html.twig', [
            'controller_name' => 'ProjectController',
            'projects' => $projects
        ]);
    }

    /**
     * @Route("/add", name="add_project")
     * @param Request $request
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addProject(Request $request, UserInterface $user)
    {
        /** @var User $user */
        $project = new Project();
        $form = $this->createForm(ProjectFormType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $project->setAuthor($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($project);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre projet a bien été créé'
            );

            return $this->redirectToRoute('project');
        }

        return $this->render('project/add.html.twig', [
            'controller_name' => 'Add project',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit_project")
     * @param Request $request
     * @param Project $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editProject(Request $request, Project $id)
    {
        $form = $this->createForm(ProjectFormType::class, $id);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($id);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre projet a bien été mit à jour'
            );

            return $this->redirectToRoute('project');
        }

        return $this->render('project/add.html.twig', [
            'controller_name' => 'Add project',
            'form' => $form->createView()
        ]);
    }
}
