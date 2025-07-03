<?php
namespace App\Command;

use App\Entity\Notice;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateRandomNoticesCommand extends Command
{
    // Retiré : protected static $defaultName

    private $em;
    private $bookRepo;
    private $userRepo;
    private $authorRepo;

    public function __construct(EntityManagerInterface $em, BookRepository $bookRepo, UserRepository $userRepo, AuthorRepository $authorRepo)
    {
        parent::__construct();
        $this->em = $em;
        $this->bookRepo = $bookRepo;
        $this->userRepo = $userRepo;
        $this->authorRepo = $authorRepo;
    }

    protected function configure()
    {
        $this->setName('app:generate-random-notices')
             ->setDescription('Génère des notices (avis) aléatoires pour les livres');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $books = $this->bookRepo->findAll();
        $users = $this->userRepo->findAll();
        $authors = $this->authorRepo->findAll();

        if (count($users) === 0) {
            $output->writeln('Aucun utilisateur trouvé.');
            return Command::FAILURE;
        }
        if (count($books) === 0) {
            $output->writeln('Aucun livre trouvé.');
            return Command::FAILURE;
        }

        $sampleComments = [
            'Excellent livre, je recommande vivement !',
            'Très instructif, mais parfois un peu technique.',
            'Pas mon genre, mais bien écrit.',
            'Je l\'ai lu plusieurs fois, un classique.',
            'Décevant, je m\'attendais à mieux.',
            'Parfait pour les débutants.',
            'Une vraie perle, à lire absolument.',
            'Moyen, quelques passages intéressants.',
            'J\'ai adoré le style d\'écriture.',
            'Un peu trop long à mon goût.'
        ];

        foreach ($books as $book) {
            $noticeCount = rand(1, 5);

            for ($i = 0; $i < $noticeCount; $i++) {
                $notice = new Notice();

                $user = $users[array_rand($users)];
                $notice->setUser($user);

                $notice->setBook($book);

                $notice->setNote(rand(1, 5));

                $notice->setCommentaire($sampleComments[array_rand($sampleComments)]);

                $notice->setDate(new \DateTime('-' . rand(0, 180) . ' days'));

                if (!empty($authors) && rand(0, 1)) {
                    $notice->setAuthor($authors[array_rand($authors)]);
                }

                $this->em->persist($notice);
            }
        }

        $this->em->flush();

        $output->writeln('Notices générées avec succès !');
        return Command::SUCCESS;
    }
}
