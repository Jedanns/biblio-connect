<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class CategoryFixtures extends Fixture
{
    public const CATEGORIES = [
        'Roman', 'Science-Fiction', 'Fantasy', 'Policier',
        'Biographie', 'Histoire', 'Sciences', 'Jeunesse',
    ];

    public function load(ObjectManager $manager): void
    {
        $slugger = new AsciiSlugger('fr');

        foreach (self::CATEGORIES as $i => $name) {
            $category = new Category();
            $category->setName($name);
            $category->setSlug(strtolower($slugger->slug($name)));
            $category->setDescription('Catégorie ' . $name);
            $manager->persist($category);
            $this->addReference('category-' . $i, $category);
        }

        $manager->flush();
    }
}
