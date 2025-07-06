<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création de quelques auteurs
        $author1 = new Author();
        $author1->setFullname('Victor Hugo');
        $author1->setBirthdate(new \DateTime('1802-02-26'));
        $author1->setBiography('Écrivain français célèbre pour Les Misérables.');
        $manager->persist($author1);

        $author2 = new Author();
        $author2->setFullname('Jules Verne');
        $author2->setBirthdate(new \DateTime('1828-02-08'));
        $author2->setBiography('Auteur français de science-fiction, célèbre pour Voyage au centre de la Terre.');
        $manager->persist($author2);

        // Création de quelques catégories avec description (obligatoire)
        $cat1 = new Category();
        $cat1->setName('Roman');
        $cat1->setDescription('Catégorie des romans classiques.');
        $manager->persist($cat1);

        $cat2 = new Category();
        $cat2->setName('Science-fiction');
        $cat2->setDescription('Catégorie des œuvres de science-fiction.');
        $manager->persist($cat2);

        // Création d’un livre disponible
        $book1 = new Book();
        $book1->setTitle('Les Misérables')
              ->setPublicationDate(new \DateTime('1862-01-01'))
              ->setIsbn('978-1234567890')
              ->setAvailable(true)
              ->setCoverImage('https://example.com/lesmiserables.jpg')
              ->addAuthor($author1)
              ->addCategory($cat1);
        $manager->persist($book1);

        // Création d’un livre indisponible
        $book2 = new Book();
        $book2->setTitle('Voyage au centre de la Terre')
              ->setPublicationDate(new \DateTime('1864-01-01'))
              ->setIsbn('978-0987654321')
              ->setAvailable(false)
              ->setCoverImage('https://example.com/voyage.jpg')
              ->addAuthor($author2)
              ->addCategory($cat2);
        $manager->persist($book2);

        $manager->flush();
    }
}
