<?php
namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            ['BD', 'Bande dessinée, comics, mangas'],
            ['Roman', 'Fiction narrative longue'],
            ['Essai', 'Analyse, réflexion sur un sujet'],
            ['Poésie', 'Œuvres en vers ou en prose poétique'],
        ];

        foreach ($categories as [$name, $desc]) {
            $category = new Category();
            $category->setName($name);
            $category->setDescription($desc);
            $manager->persist($category);
        }

        $genres = [
            ['Fantasy', 'Univers imaginaires et magiques'],
            ['Science-fiction', 'Technologie, espace, futur'],
            ['Policier', 'Enquêtes, mystères, crimes'],
            ['Romance', 'Histoires d’amour'],
            ['Thriller', 'Suspense et tension'],
        ];

        foreach ($genres as [$name, $desc]) {
            $genre = new Genre();
            $genre->setName($name);
            $genre->setDescription($desc);
            $manager->persist($genre);
        }

        $manager->flush();
    }
}
