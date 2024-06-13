<?php

namespace App\Controller;

use App\Entity\Credentials;
use App\Form\CredentialsType;
use App\Repository\CredentialsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/credentials')]
class CredentialsController extends AbstractController
{
    #[Route('/', name: 'app_credentials_index', methods: ['GET'])]
    public function index(CredentialsRepository $credentialsRepository): Response
    {
        return $this->render('credentials/index.html.twig', [
            'credentials' => $credentialsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_credentials_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $credential = new Credentials();
        $form = $this->createForm(CredentialsType::class, $credential);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($credential);
            $entityManager->flush();

            return $this->redirectToRoute('app_credentials_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('credentials/new.html.twig', [
            'credential' => $credential,
            'form' => $form,
        ]);
    }

    #[Route('/{referenceid}', name: 'app_credentials_show', methods: ['GET'])]
    public function show(Credentials $credential): Response
    {
        return $this->render('credentials/show.html.twig', [
            'credential' => $credential,
        ]);
    }

    #[Route('/{referenceid}/edit', name: 'app_credentials_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Credentials $credential, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CredentialsType::class, $credential);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_credentials_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('credentials/edit.html.twig', [
            'credential' => $credential,
            'form' => $form,
        ]);
    }

    #[Route('/{referenceid}', name: 'app_credentials_delete', methods: ['POST'])]
    public function delete(Request $request, Credentials $credential, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$credential->getReferenceid(), $request->request->get('_token'))) {
            $entityManager->remove($credential);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_credentials_index', [], Response::HTTP_SEE_OTHER);
    }
}
