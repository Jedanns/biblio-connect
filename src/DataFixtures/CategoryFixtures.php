<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class CategoryFixtures extends Fixture
{
    public const CATEGORIES = [
        'Roman' => 'Fiction narrative longue explorant la condition humaine à travers des personnages et des intrigues.',
        'Science-Fiction' => 'Récits spéculatifs fondés sur des avancées scientifiques ou technologiques, réelles ou imaginées.',
        'Fantasy' => 'Univers imaginaires peuplés de magie, de créatures mythiques et de quêtes épiques.',
        'Policier' => 'Enquêtes criminelles, suspense et déductions logiques autour de mystères à résoudre.',
        'Biographie' => 'Récits de vies réelles, documentés et restitués dans leur contexte historique.',
        'Histoire' => 'Ouvrages analysant les événements, civilisations et transformations du passé.',
        'Sciences' => 'Vulgarisation et approfondissement des connaissances en sciences naturelles et formelles.',
        'Jeunesse' => 'Littérature destinée aux enfants et adolescents, du conte illustré au roman initiatique.',
    ];

    public function load(ObjectManager $manager): void
    {
        $slugger = new AsciiSlugger('fr');
        $i = 0;

        foreach (self::CATEGORIES as $name => $description) {
            $category = new Category();
            $category->setName($name);
            $category->setSlug(strtolower($slugger->slug($name)));
            $category->setDescription($description);
            $manager->persist($category);
            $this->addReference('category-' . $i, $category);
            $i++;
        }

        $manager->flush();
    }
}
