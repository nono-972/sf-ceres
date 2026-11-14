<?php

namespace App\Controller;

use App\Entity\Cerescop;
use App\Form\CerescopType;
use App\Repository\CerescopRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cerescop')]
final class CerescopController extends AbstractController
{
    #[Route(name: 'app_cerescop_index', methods: ['GET'])]
    public function index(CerescopRepository $cerescopRepository): Response
    {
        return $this->render('cerescop/index.html.twig', [
            'cerescops' => $cerescopRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cerescop_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,FileUploader $fileUploader): Response
    {
        $cerescop = new Cerescop();
        $form = $this->createForm(CerescopType::class, $cerescop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cerescop);
            $entityManager->flush();
            $fileUploader->upload($cerescop);
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $cerescop->setImage($imageFileName);
            }

            return $this->redirectToRoute('app_cerescop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cerescop/new.html.twig', [
            'cerescop' => $cerescop,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cerescop_show', methods: ['GET'])]
    public function show(Cerescop $cerescop): Response
    {
        return $this->render('cerescop/show.html.twig', [
            'cerescop' => $cerescop,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cerescop_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cerescop $cerescop, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(CerescopType::class, $cerescop);
        $form->handleRequest($request);
        $oldfilename = $cerescop->getImage();


        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                if ($oldfilename) {
                    $oldFilepath = $this->getParameter('images_directory').'/'.$oldfilename;
                    if ($filesystem->exists($oldFilepath)) {
                        $filesystem->remove($oldFilepath);
                    }
                }
                $cerescop->setImage($imageFileName);
            }           
            
            $entityManager->flush();

            return $this->redirectToRoute('app_cerescop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cerescop/edit.html.twig', [
            'cerescop' => $cerescop,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cerescop_delete', methods: ['POST'])]
    public function delete(Request $request, Cerescop $cerescop, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cerescop->getId(), $request->getPayload()->getString('_token'))) {
            $oldFilename = $cerescop->getImage();

            if ($oldFilename) {
                $oldFilepath = $this->getParameter('images_directory').'/'.$oldFilename;
                if ($filesystem->exists($oldFilepath)) {
                    $filesystem->remove($oldFilepath);
                }
                $entityManager->remove($cerescop);
                $entityManager->flush();
            }
        }
        return $this->redirectToRoute('app_cerescop_index', [], Response::HTTP_SEE_OTHER);
    }
}
