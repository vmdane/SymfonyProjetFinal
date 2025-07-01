<?php
namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Book;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $categorys = [];

        foreach (['Novel', 'BD', 'Essay', 'Histoire', 'Science'] as $name) {
            $category = new Category();
            $category->setNom($name);
            $category->setDescription($faker->sentence()); // ← c’est ce champ qui est obligatoire
            $manager->persist($category);
            $categorys[] = $category;
        }

        // Exemple de création de books
        for ($i = 0; $i < 50; $i++) {
            $book = new Book();
            $book->setTitre($faker->sentence(3));
            $book->setDatePublication($faker->dateTimeBetween('-10 years', 'now'));
            $book->setIsbn($faker->isbn13());
            $book->setDisponible($faker->boolean());
            $book->setImageCouverture($faker->imageUrl(200, 300, 'books'));

            // Ajouter une catégorie aléatoire
            $book->addCategory($faker->randomElement($categorys));

            $manager->persist($book);
        }

        $manager->flush();
    }
}
