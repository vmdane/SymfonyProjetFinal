<?php

namespace App\Service;

use App\Entity\Book;
use App\Entity\Author;
use App\Repository\BookRepository;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GoogleBooksService
{
    private HttpClientInterface $client;
    private string $apiKey;
    private EntityManagerInterface $entityManager;
    private BookRepository $bookRepository;
    private AuthorRepository $authorRepository;

    public function __construct(
        HttpClientInterface $client,
        string $apiKey,
        EntityManagerInterface $entityManager,
        BookRepository $bookRepository,
        AuthorRepository $authorRepository
    ) {
        $this->client = $client;
        $this->apiKey = $apiKey;
        $this->entityManager = $entityManager;
        $this->bookRepository = $bookRepository;
        $this->authorRepository = $authorRepository;
    }

    public function searchBooks(string $query): array
    {
        $response = $this->client->request('GET', 'https://www.googleapis.com/books/v1/volumes', [
            'query' => [
                'q' => $query,
                'key' => $this->apiKey,
            ]
        ]);

        return $response->toArray();
    }

    public function getUserBookshelves(string $accessToken): array
    {
        $response = $this->client->request('GET', 'https://www.googleapis.com/books/v1/mylibrary/bookshelves', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
        ]);

        return $response->toArray();
    }

    public function getBooksInShelf(string $accessToken, int $shelfId): array
    {
        $response = $this->client->request('GET', "https://www.googleapis.com/books/v1/mylibrary/bookshelves/$shelfId/volumes", [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
        ]);

        return $response->toArray();
    }

    public function syncBooksInShelf(string $accessToken, int $shelfId): void
    {
        error_log("Début syncBooksInShelf, shelfId: $shelfId");

        $response = $this->getBooksInShelf($accessToken, $shelfId);
        error_log('Réponse brute : ' . json_encode($response));

        $volumes = $response['items'] ?? [];
        error_log('Nombre de volumes récupérés : ' . count($volumes));

        foreach ($volumes as $volume) {
            $googleId = $volume['id'];
            $volumeInfo = $volume['volumeInfo'] ?? [];

            error_log("Traitement du livre Google ID : $googleId");

            $book = $this->bookRepository->findOneBy(['googleBookId' => $googleId]);
            if (!$book) {
                error_log("Nouveau livre détecté, création");
                $book = new Book();
                $book->setGoogleBookId($googleId);
            } else {
                error_log("Livre existant trouvé en base");
            }

            $book->setTitle($volumeInfo['title'] ?? 'Title inconnu');
            $book->setDescription($volumeInfo['description'] ?? null);

            if (!empty($volumeInfo['publishedDate'])) {
                try {
                    $date = new \DateTime($volumeInfo['publishedDate']);
                    $book->setPublicationDate($date);
                } catch (\Exception $e) {
                    error_log('Date de publication invalide pour le livre ' . $googleId);
                }
            }

            if (!empty($volumeInfo['imageLinks']['thumbnail'])) {
                $book->setCoverImage($volumeInfo['imageLinks']['thumbnail']);
            }

            if (!empty($volumeInfo['authors']) && is_array($volumeInfo['authors'])) {
                foreach ($book->getAuthors() as $author) {
                    $book->removeAuthor($author);
                }
                foreach ($volumeInfo['authors'] as $authorName) {
                    $author = $this->authorRepository->findOneBy(['name' => $authorName]);
                    if (!$author) {
                        error_log("Nouvel auteur créé : $authorName");
                        $author = new Author();
                        $author->setName($authorName);
                        $this->entityManager->persist($author);
                    }
                    $book->addAuthor($author);
                }
            }

            $this->entityManager->persist($book);
        }

        $this->entityManager->flush();

        error_log("Fin syncBooksInShelf, shelfId: $shelfId");
    }
}
