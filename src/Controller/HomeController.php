<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(BookRepository $bookRepository): Response
    {
        // Récupérer 5 livres aléatoires (par exemple)
        $books = $bookRepository->findRandomBooks(8);

        return $this->render('index.html.twig', [
            'books' => $books,
        ]);
    }
}