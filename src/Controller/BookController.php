<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookForm;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/book')]
final class BookController extends AbstractController
{
    #[Route(name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookForm::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/book/{id}', name: 'app_book_show')]
    public function show(Book $book): Response
    {
        $fakeReviews = [
            [
                'username' => 'Alice',
                'commentaire' => 'A fascinating book, highly recommend it!',
                'note' => 5,
            ],
            [
                'username' => 'Bob',
                'commentaire' => 'Not bad, but a bit lengthy.',
                'note' => 3,
            ],
            [
                'username' => 'Charlie',
                'commentaire' => 'An enjoyable and enriching read.',
                'note' => 4,
            ],
            [
                'username' => 'Denise',
                'commentaire' => 'Didn’t quite get into it, too complex for me.',
                'note' => 2,
            ],
        ];

        shuffle($fakeReviews);

        return $this->render('book/show.html.twig', [
            'book' => $book,
            'fakeReviews' => $fakeReviews,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_book_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookForm::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_delete', methods: ['POST'])]
    public function delete(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/emprunter', name: 'app_book_emprunter', methods: ['POST'])]
    public function emprunter(Book $book, Request $request, EntityManagerInterface $em): RedirectResponse
    {
        if (!$this->isCsrfTokenValid('emprunter'.$book->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        if ($book->isDisponible()) {
            $book->setDisponible(false);
            $em->flush();
            $this->addFlash('success', 'Livre emprunté avec succès !');
        } else {
            $this->addFlash('warning', 'Ce livre est déjà emprunté.');
        }

        return $this->redirectToRoute('app_book_index'); // ou autre route où tu veux revenir
    }

    #[Route('/{id}/favorite', name: 'app_book_favoris', methods: ['POST'])]
    public function favorite(Book $book): RedirectResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('You must be logged in to add to favorites.');
        }

        // Example: add the book to user's favorites (pseudo-code)
        // $user->addFavori($book);
        // $entityManager->flush();

        // You should inject EntityManagerInterface here if needed
        // and persist the relation.

        $this->addFlash('success', 'Book added to favorites!');

        return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
    }

    #[Route('/book/{id}/favorite', name: 'app_book_favoris', methods: ['POST'])]
    public function addToFavorite(int $id, Request $request, EntityManagerInterface $em): RedirectResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour ajouter un favori.');
        }

        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('favorite'.$id, $token)) {
            throw $this->createAccessDeniedException('Token CSRF invalide');
        }

        // Récupérer le livre en base
        $book = $em->getRepository(Book::class)->find($id);
        if (!$book) {
            throw $this->createNotFoundException('Livre non trouvé');
        }

        // Ajouter le livre aux favorite (par exemple, une relation ManyToMany User <-> Book favorite)
        if (!$user->getFavorite()->contains($book)) {
            $user->addFavori($book);
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Livre ajouté aux favorite');
        } else {
            $this->addFlash('info', 'Ce livre est déjà dans vos favorite');
        }

        return $this->redirectToRoute('app_book_show', ['id' => $id]);
    }
}
