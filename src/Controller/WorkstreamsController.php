<?php

namespace App\Controller;

use App\Entity\Workstreams;
use App\Form\WorkstreamsType;
use App\Repository\WorkstreamsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/workstreams')]
class WorkstreamsController extends AbstractController
{
    #[Route('/', name: 'app_workstreams_index', methods: ['GET'])]
    public function index(WorkstreamsRepository $workstreamsRepository): Response
    {
        return $this->render('workstreams/index.html.twig', [
            'workstreams' => $workstreamsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_workstreams_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $workstream = new Workstreams();
        $form = $this->createForm(WorkstreamsType::class, $workstream);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($workstream);
            $entityManager->flush();

            return $this->redirectToRoute('app_workstreams_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('workstreams/new.html.twig', [
            'workstream' => $workstream,
            'form' => $form,
        ]);
    }

    #[Route('/{idworkstream}', name: 'app_workstreams_show', methods: ['GET'])]
    public function show(Workstreams $workstream): Response
    {
        return $this->render('workstreams/show.html.twig', [
            'workstream' => $workstream,
        ]);
    }

    #[Route('/{idworkstream}/edit', name: 'app_workstreams_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Workstreams $workstream, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WorkstreamsType::class, $workstream);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_workstreams_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('workstreams/edit.html.twig', [
            'workstream' => $workstream,
            'form' => $form,
        ]);
    }

    #[Route('/{idworkstream}', name: 'app_workstreams_delete', methods: ['POST'])]
    public function delete(Request $request, Workstreams $workstream, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$workstream->getIdworkstream(), $request->request->get('_token'))) {
            $entityManager->remove($workstream);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_workstreams_index', [], Response::HTTP_SEE_OTHER);
    }
}
