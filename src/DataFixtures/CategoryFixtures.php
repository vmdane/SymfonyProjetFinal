<?php
namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        $categorys = [];
        $names = ['Novel', 'BD', 'Essay'];  
        foreach ($names as $name) {
            $cat = new Category();
            $cat->setNom($name);
            $cat->setDescription($faker->sentence());
            $manager->persist($cat);
            $categorys[] = $cat;
        }
        $manager->flush();
    }
}
