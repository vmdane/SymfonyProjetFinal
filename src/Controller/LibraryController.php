<?php

namespace App\Controller;

use App\Repository\LoanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{
    #[Route('/ma-bibliotheque', name: 'app_library')]
    public function index(LoanRepository $loanRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté');
        }

        $currentLoans = $loanRepository->findBy([
            'user' => $user,
            'dateRetour' => null,
        ]);

        $pastLoans = $loanRepository->findBy([
            'user' => $user,
        ], ['dateRetour' => 'DESC']);

        $pastLoans = array_filter($pastLoans, fn($loan) => $loan->getDateRetour() !== null);

        return $this->render('library/my_library.html.twig', [
            'currentLoans' => $currentLoans,
            'pastLoans' => $pastLoans,
        ]);
    }

}