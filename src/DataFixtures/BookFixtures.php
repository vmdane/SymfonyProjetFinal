<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $authors   = $manager->getRepository(Author::class)->findAll();
        $categorys = $manager->getRepository(Category::class)->findAll();

        // Récupérer tous les fichiers images dans public/images
        $imageFiles = array_filter(scandir('public/images'), function($file) {
            return preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
        });

        for ($i = 1; $i <= 50; $i++) {
            $book = new Book();
            $book->setTitre($faker->sentence(3));
            $book->setDatePublication($faker->dateTimeBetween('-30 years', 'now'));
            $book->setIsbn($faker->isbn13());
            $book->setDisponible($faker->boolean(80));

            // Choisir une image aléatoire parmi celles trouvées
            if (count($imageFiles) > 0) {
                $randomImage = $faker->randomElement($imageFiles);
                $book->setImageCouverture($randomImage);
            } else {
                $book->setImageCouverture('default.jpg'); // fallback si dossier vide
            }

            if (count($authors) > 0) {
                foreach ($faker->randomElements($authors, rand(1, min(3, count($authors)))) as $a) {
                    $book->addAuthor($a);
                }
            }

            if (count($categorys) > 0) {
                foreach ($faker->randomElements($categorys, rand(1, min(2, count($categorys)))) as $c) {
                    $book->addCategory($c);
                }
            }

            $manager->persist($book);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            AuthorFixtures::class,
        ];
    }
}
