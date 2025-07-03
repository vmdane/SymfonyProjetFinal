<?php

// src/Controller/BibliothequeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BibliothequeController extends AbstractController
{
    #[Route('/ma-bibliotheque', name: 'ma_bibliotheque')]
    public function index(): Response
    {
        // Ici tu récupéreras les emprunts de l'utilisateur connecté

        return $this->render('bibliotheque/index.html.twig', [
            // Passe les données nécessaires ici
        ]);
    }
}
