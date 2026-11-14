<?php

namespace App\Controller;

use App\Entity\JournauxPapier;
use App\Form\JournauxPapierType;
use App\Repository\JournauxPapierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/journaux_papier')]
final class JournauxPapierController extends AbstractController
{
    #[Route(name: 'app_journaux_papier_index', methods: ['GET'])]
    public function index(JournauxPapierRepository $journauxPapierRepository): Response
    {
        return $this->render('journaux_papier/index.html.twig', [
            'journaux_papiers' => $journauxPapierRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_journaux_papier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $journauxPapier = new JournauxPapier();
        $form = $this->createForm(JournauxPapierType::class, $journauxPapier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($journauxPapier);
            $entityManager->flush();

            return $this->redirectToRoute('app_journaux_papier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('journaux_papier/new.html.twig', [
            'journaux_papier' => $journauxPapier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_journaux_papier_show', methods: ['GET'])]
    public function show(JournauxPapier $journauxPapier): Response
    {
        return $this->render('journaux_papier/show.html.twig', [
            'journaux_papier' => $journauxPapier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_journaux_papier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, JournauxPapier $journauxPapier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JournauxPapierType::class, $journauxPapier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_journaux_papier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('journaux_papier/edit.html.twig', [
            'journaux_papier' => $journauxPapier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_journaux_papier_delete', methods: ['POST'])]
    public function delete(Request $request, JournauxPapier $journauxPapier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$journauxPapier->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($journauxPapier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_journaux_papier_index', [], Response::HTTP_SEE_OTHER);
    }
}
