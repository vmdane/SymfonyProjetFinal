<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Loan;
use App\Form\LoanForm;
use App\Repository\LoanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/loan')]
final class LoanController extends AbstractController
{
    #[Route(name: 'app_loan_index', methods: ['GET'])]
    public function index(LoanRepository $loanRepository): Response
    {
        return $this->render('loan/index.html.twig', [
            'loans' => $loanRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_loan_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $loan = new Loan();
        $form = $this->createForm(LoanForm::class, $loan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($loan);
            $entityManager->flush();

            return $this->redirectToRoute('app_loan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('loan/new.html.twig', [
            'loan' => $loan,
            'form' => $form,
        ]);
    }

    #[Route('/emprunter/{id}', name: 'app_loan_emprunter', methods: ['POST'])]
    public function emprunter(Book $book, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour emprunter un livre.');
            return $this->redirectToRoute('app_login');
        }

        if (!$this->isCsrfTokenValid('emprunter' . $book->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token invalide.');
        }

        // Créer un nouvel emprunt
        $loan = new Loan();
        $loan->setUser($user);
        $loan->setBook($book);
        $loan->setDateDebut(new \DateTime());
        $loan->setDateFin((new \DateTime())->modify('+14 days'));
        $loan->setStatut('en cours');

        $entityManager->persist($loan);
        $entityManager->flush();

        $this->addFlash('success', 'Livre emprunté avec succès !');

        return $this->redirectToRoute('app_library');
    }


    #[Route('/{id}', name: 'app_loan_show', methods: ['GET'])]
    public function show(Loan $loan): Response
    {
        return $this->render('loan/show.html.twig', [
            'loan' => $loan,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_loan_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Loan $loan, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LoanForm::class, $loan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_loan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('loan/edit.html.twig', [
            'loan' => $loan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_loan_delete', methods: ['POST'])]
    public function delete(Request $request, Loan $loan, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$loan->getId(), $request->request->get('_token'))) {
            $entityManager->remove($loan);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_loan_index', [], Response::HTTP_SEE_OTHER);
    }
}
