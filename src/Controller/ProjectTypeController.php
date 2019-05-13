<?php

namespace App\Controller;

use App\Entity\ProjectType;
use App\Form\ProjectTypeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProjectTypeController
 * @package App\Controller
 * @Route("/project/type")
 */
class ProjectTypeController extends AbstractController
{
    /**
     * @Route("/", name="project_type")
     */
    public function index()
    {
        $projectsType = $this->getDoctrine()->getRepository(ProjectType::class)->findAll();

        return $this->render('project_type/index.html.twig', [
            'controller_name' => 'ProjectTypeController',
            'projectsType' => $projectsType
        ]);
    }

    /**
     * @Route("/add", name="add_project_type")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addProjectType(Request $request)
    {
        $projectType = new ProjectType();
        $form = $this->createForm(ProjectTypeType::class, $projectType);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($projectType);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Formulaire bien envoyé'
            );

            return $this->redirectToRoute('project_type');
        }

        return $this->render('project_type/add.html.twig', [
            'controller_name' => 'ProjectTypeController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit_project_type")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editProjectType(Request $request, ProjectType $id)
    {
        $form = $this->createForm(ProjectTypeType::class, $id);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($id);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Formulaire bien envoyé'
            );

            return $this->redirectToRoute('project_type');
        }

        return $this->render('project_type/add.html.twig', [
            'controller_name' => 'ProjectTypeController',
            'form' => $form->createView(),
        ]);
    }
}
