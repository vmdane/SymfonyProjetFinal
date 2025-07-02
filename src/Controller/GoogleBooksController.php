<?php

namespace App\Controller;

use App\Service\GoogleBooksService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GoogleBooksController extends AbstractController
{
    #[Route('/books/search', name: 'google_books_search')]
    public function search(Request $request, GoogleBooksService $booksService): Response
    {
        $query = $request->query->get('q', 'Symfony');

        $results = $booksService->searchBooks($query);

        return $this->render('google_books/search.html.twig', [
            'books' => $results['items'] ?? [],
            'query' => $query,
        ]);
    }
}
