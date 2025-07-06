<?php

namespace App\Controller;

use App\Service\GoogleBooksService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;



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
    #[Route('/books/library', name: 'google_books_library')]
    public function library(SessionInterface $session, GoogleBooksService $booksService): Response
    {
        $accessToken = $session->get('google_access_token');

        if (!$accessToken) {
            return $this->redirectToRoute('connect_google');
        }

        $bookshelves = $booksService->getUserBookshelves($accessToken);
        $booksInFavorites = $booksService->getBooksInShelf($accessToken, 0);
        dump($booksInFavorites);

        dump($accessToken);


        return $this->render('google_books/library.html.twig', [
            'shelves' => $bookshelves['items'] ?? [],
        ]);
    }
}
