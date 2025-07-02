<?php

namespace App\Service;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GoogleBooksService
{
    private HttpClientInterface $client;
    private string $apiKey;

    public function __construct(HttpClientInterface $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
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




}
