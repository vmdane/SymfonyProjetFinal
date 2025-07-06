<?php

namespace App\EventListener;

use App\Entity\Loan;
use App\Entity\Notification;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Psr\Log\LoggerInterface;

class LoanListener
{
    public function __construct(
        private EntityManagerInterface $em,
        private MailerInterface $mailer,
        private LoggerInterface $logger

    ) {}

    public function prePersist(Loan $loan, PrePersistEventArgs $event): void
    {
        if ($loan->getStatus() === 'requested') {
            $this->createNotification(
                $loan,
                $loan->getLender(),
                "Nouvelle demande de prêt pour le livre : " . $loan->getBook()?->getTitle(),
                'loan_request'
            );
        }
    }

    public function postUpdate(Loan $loan, PostUpdateEventArgs $event): void
    {
        $em = $event->getObjectManager();
        $uow = $em->getUnitOfWork();
        $changeSet = $uow->getEntityChangeSet($loan);

        if (isset($changeSet['status'])) {
            $newStatus = $changeSet['status'][1];

            $content = match ($newStatus) {
                'accepted' => "Votre demande pour le livre « " . $loan->getBook()?->getTitle() . " » a été acceptée.",
                'refused' => "Votre demande pour le livre « " . $loan->getBook()?->getTitle() . " » a été refusée.",
                'returned' => "Le livre « " . $loan->getBook()?->getTitle() . " » a été retourné.",
                default => null,
            };

            if ($content) {
                $user = in_array($newStatus, ['accepted', 'refused']) ? $loan->getBorrower() : $loan->getLender();
                $type = 'loan_' . $newStatus;

                $this->createNotification($loan, $user, $content, $type);
            }
        }
    }

    private function createNotification(Loan $loan, $user, string $content, string $type): void
    {
        $notification = (new Notification())
            ->setUser($user)
            ->setContent($content)
            ->setType($type)
            ->setBook($loan->getBook())
            ->setSendDate(new \DateTime())
            ->setIsRead(false);

        $this->em->persist($notification);
        $this->em->flush();

        if ($user && $user->getEmail()) {
            $email = (new Email())
                ->from('notifications@bibliotheque.karen-gueppois.fr')
                ->to($user->getEmail())
                ->subject('Nouvelle notification')
                ->text("Bonjour {$user->getFirstname()},\n\n" . $content);

            try {
                $this->mailer->send($email);
                error_log("Email sent to " . $user->getEmail());
            } catch (\Throwable $e) {
                error_log("Mailer error: " . $e->getMessage());
            }
        } else {
            error_log("No user or user email found for notification.");
        }
    }
}
