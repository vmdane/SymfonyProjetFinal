<?php

namespace App\Controller;

use App\Entity\Notice;
use App\Form\NoticeForm;
use App\Repository\NoticeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/notice')]
final class NoticeController extends AbstractController
{
    #[Route(name: 'app_notice_index', methods: ['GET'])]
    public function index(NoticeRepository $noticeRepository): Response
    {
        return $this->render('notice/index.html.twig', [
            'notice' => $noticeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_notice_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $avi = new Notice();
        $form = $this->createForm(NoticeForm::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($avi);
            $entityManager->flush();

            return $this->redirectToRoute('app_notice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('notice/new.html.twig', [
            'avi' => $avi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_notice_show', methods: ['GET'])]
    public function show(Notice $avi): Response
    {
        return $this->render('notice/show.html.twig', [
            'avi' => $avi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_notice_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Notice $avi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NoticeForm::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_notice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('notice/edit.html.twig', [
            'avi' => $avi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_notice_delete', methods: ['POST'])]
    public function delete(Request $request, Notice $avi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avi->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($avi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_notice_index', [], Response::HTTP_SEE_OTHER);
    }
}
