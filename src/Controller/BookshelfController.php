<?php

namespace App\Controller;

use App\Entity\Bookshelf;
use App\Form\BookshelfForm;
use App\Repository\BookshelfRepository;
use App\Repository\LoanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/bookshelf')]
final class BookshelfController extends AbstractController
{
    #[Route('/ma-bibliotheque', name: 'ma_bibliotheque', methods: ['GET'])]
    public function libraryOverview(
        LoanRepository $loanRepository,
        BookshelfRepository $bookshelfRepository
    ): Response {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté');
        }

        $currentLoans = $loanRepository->findBy([
            'borrower' => $user,
            'returnDate' => null,
        ]);

        $pastLoans = $loanRepository->findBy([
            'borrower' => $user,
        ], ['returnDate' => 'DESC']);

        $pastLoans = array_filter($pastLoans, fn($loan) => $loan->getReturnDate() !== null);

        $userBookshelves = $bookshelfRepository->findBy(['user' => $user]);

        return $this->render('library/my_library.html.twig', [
            'currentLoans' => $currentLoans,
            'pastLoans' => $pastLoans,
            'bookshelves' => $userBookshelves,
        ]);
    }

    #[Route(name: 'app_bookshelf_index', methods: ['GET'])]
    public function index(BookshelfRepository $bookshelfRepository): Response
    {
        return $this->render('bookshelf/index.html.twig', [
            'bookshelves' => $bookshelfRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_bookshelf_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $bookshelf = new Bookshelf();
        $form = $this->createForm(BookshelfForm::class, $bookshelf);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bookshelf);
            $entityManager->flush();

            return $this->redirectToRoute('app_bookshelf_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bookshelf/new.html.twig', [
            'bookshelf' => $bookshelf,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bookshelf_show', methods: ['GET'])]
    public function show(Bookshelf $bookshelf): Response
    {
        return $this->render('bookshelf/show.html.twig', [
            'bookshelf' => $bookshelf,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bookshelf_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bookshelf $bookshelf, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookshelfForm::class, $bookshelf);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_bookshelf_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bookshelf/edit.html.twig', [
            'bookshelf' => $bookshelf,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bookshelf_delete', methods: ['POST'])]
    public function delete(Request $request, Bookshelf $bookshelf, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bookshelf->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($bookshelf);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_bookshelf_index', [], Response::HTTP_SEE_OTHER);
    }
}
