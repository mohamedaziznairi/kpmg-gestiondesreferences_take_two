<?php

namespace App\Controller;

use App\Entity\Objectives;
use App\Form\ObjectivesType;
use App\Repository\ObjectivesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/objectives')]
class ObjectivesController extends AbstractController
{
    #[Route('/', name: 'app_objectives_index', methods: ['GET'])]
    public function index(ObjectivesRepository $objectivesRepository): Response
    {
        return $this->render('objectives/index.html.twig', [
            'objectives' => $objectivesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_objectives_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $objective = new Objectives();
        $form = $this->createForm(ObjectivesType::class, $objective);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($objective);
            $entityManager->flush();

            return $this->redirectToRoute('app_objectives_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('objectives/new.html.twig', [
            'objective' => $objective,
            'form' => $form,
        ]);
    }

    #[Route('/{idobjectif}', name: 'app_objectives_show', methods: ['GET'])]
    public function show(Objectives $objective): Response
    {
        return $this->render('objectives/show.html.twig', [
            'objective' => $objective,
        ]);
    }

    #[Route('/{idobjectif}/edit', name: 'app_objectives_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Objectives $objective, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ObjectivesType::class, $objective);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_objectives_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('objectives/edit.html.twig', [
            'objective' => $objective,
            'form' => $form,
        ]);
    }

    #[Route('/{idobjectif}', name: 'app_objectives_delete', methods: ['POST'])]
    public function delete(Request $request, Objectives $objective, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$objective->getIdobjectif(), $request->request->get('_token'))) {
            $entityManager->remove($objective);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_objectives_index', [], Response::HTTP_SEE_OTHER);
    }
}
