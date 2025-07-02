<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Book;
use App\Entity\Loan;
use App\Entity\UserBook;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AllFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // --- USERS ---
        $users = [];
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail($faker->unique()->safeEmail);
            $user->setRoles(['ROLE_USER']);
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
            $user->setPassword($hashedPassword); // à remplacer par un hash en vrai
            $user->setName($faker->lastName);
            $user->setFirstname($faker->firstName);
            $user->setCreateAt($faker->dateTimeBetween('-1 years', 'now'));
            $user->setIsVerified($faker->boolean(80));
            $user->setGoogleId(''); // null par défaut
            $manager->persist($user);
            $users[] = $user;
        }

        // --- BOOKS ---
        $books = [];
        for ($i = 0; $i < 10; $i++) {
            $book = new Book();
            $book->setTitre($faker->sentence(3));
            $book->setDatePublication($faker->dateTimeBetween('-30 years', 'now'));
            $book->setIsbn($faker->isbn13);
            $book->setDisponible($faker->boolean(70));
            $book->setImageCouverture('default.jpg');
            $book->setDescription($faker->optional()->text(200));
            $book->setGoogleBookId(null);
            $manager->persist($book);
            $books[] = $book;
        }

        // --- USERBOOKS (bibliothèque personnelle des utilisateurs) ---
        foreach ($users as $user) {
            $ownedBooks = $faker->randomElements($books, $faker->numberBetween(1, 5));
            foreach ($ownedBooks as $book) {
                $userBook = new UserBook();
                $userBook->setUser($user);
                $userBook->setBook($book);
                $userBook->setAddedAt(new \DateTimeImmutable($faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s')));
                $userBook->setAvailableForLoan($faker->boolean(70));
                $manager->persist($userBook);
            }
        }

        // --- LOANS ---
        for ($i = 0; $i < 20; $i++) {
            $loan = new Loan();

            $user = $faker->randomElement($users);
            $book = $faker->randomElement($books);

            $loan->setUser($user);
            $loan->setBook($book);

            $dateDebut = $faker->dateTimeBetween('-60 days', '-10 days');
            $loan->setDateDebut($dateDebut);

            $dateFin = (clone $dateDebut)->modify('+14 days');
            $loan->setDateFin($dateFin);

            if ($faker->boolean(60)) {
                // Prêt rendu : dateRetour entre dateDebut et maintenant
                $dateRetour = $faker->dateTimeBetween($dateDebut, 'now');
            } else {
                // Prêt non rendu : on met une fausse date dans le futur (par exemple demain)
                $dateRetour = (new \DateTime())->modify('+1 day');
            }

            $loan->setDateRetour($dateRetour);

            $loan->setStatut($faker->boolean(60) ? 'Returned' : 'On loan');



            $manager->persist($loan);
        }


        $manager->flush();
    }
}
