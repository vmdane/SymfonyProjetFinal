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
            $user->setGoogleId(''); // null par défaut
            $manager->persist($user);
            $users[] = $user;
        }

        // --- BOOKS ---
        $books = [];
        for ($i = 0; $i < 10; $i++) {
            $book = new Book();
            $book->setTitle($faker->sentence(3));
            $book->setPublicationDate($faker->dateTimeBetween('-30 years', 'now'));
            $book->setIsbn($faker->isbn13);
            $book->setAvailable($faker->boolean(70));
            $book->setCoverImage('default.jpg');
            $book->setDescription($faker->optional()->text(200));
            $book->setGoogleBookId(null);
            $manager->persist($book);
            $books[] = $book;
        }


        // --- LOANS ---
        for ($i = 0; $i < 20; $i++) {
            $loan = new Loan();

            $user = $faker->randomElement($users);
            $book = $faker->randomElement($books);

            $loan->setBook($book);

            $startDate = $faker->dateTimeBetween('-60 days', '-10 days');
            $loan->setStartDate($startDate);

            $endDate = (clone $startDate)->modify('+14 days');
            $loan->setEndDate($endDate);

            if ($faker->boolean(60)) {
                // Prêt rendu : returnDate entre startDate et maintenant
                $returnDate = $faker->dateTimeBetween($startDate, 'now');
            } else {
                // Prêt non rendu : on met une fausse date dans le futur (par exemple demain)
                $returnDate = (new \DateTime())->modify('+1 day');
            }

            $loan->setReturnDate($returnDate);

            $loan->setStatus($faker->boolean(60) ? 'Returned' : 'On loan');



            $manager->persist($loan);
        }


        $manager->flush();
    }
}
