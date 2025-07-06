<?php
// src/Controller/FavoriteController.php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{
    #[Route('/favorite', name: 'app_favorite')]
    public function index(): Response
    {
        $user = $this->getUser();

        // Exemple : récupérer la liste des livres favorite de l'utilisateur
        // Remplace cette ligne par ta logique réelle (via repository, etc.)
        $favorite = $user ? $user->getFavorite() : [];

        return $this->render('favorite/list.html.twig', [
            'favorite' => $favorite,
        ]);
    }

    #[Route('/favorite', name: 'app_favorite_list')]
    public function list(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté.');
        }

        $favorite = $user->getFavorite();

        return $this->render('favorite/list.html.twig', [
            'favorite' => $favorite,
        ]);
    }

    #[Route('/favorite/add/{id}', name: 'app_favorite_add', methods: ['POST'])]
    public function add(Book $book, EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté.');
        }

        if (!$this->isCsrfTokenValid('add_favori'.$book->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide');
        }

        $user->addFavorite($book);
        $em->flush();

        $this->addFlash('success', 'Livre ajouté aux favorite !');

        return $this->redirectToRoute('app_book_index');
    }

    #[Route('/favorite/remove/{id}', name: 'app_favorite_remove', methods: ['POST'])]
    public function remove(Book $book, EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté.');
        }

        if (!$this->isCsrfTokenValid('remove_favori'.$book->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide');
        }

        $user->removeFavorite($book);
        $em->flush();

        $this->addFlash('success', 'Livre retiré des favorite !');

        return $this->redirectToRoute('app_favorite_list');
    }
}
