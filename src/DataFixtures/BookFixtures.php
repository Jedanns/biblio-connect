<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class BookFixtures extends Fixture implements DependentFixtureInterface
{
    public const COVERS = [
        0 => 'cover-0.jpg',
        1 => 'cover-1.jpg',
        2 => 'cover-2.jpg',
        3 => 'cover-3.jpg',
        4 => 'cover-4.jpg',
        5 => 'cover-5.jpg',
        6 => 'cover-6.jpg',
        7 => 'cover-7.jpg',
        8 => 'cover-8.jpg',
        9 => 'cover-9.jpg',
        10 => 'cover-10.jpg',
        11 => 'cover-11.jpg',
        12 => 'cover-12.jpg',
        13 => 'cover-13.jpg',
        14 => 'cover-14.jpg',
        15 => 'cover-15.jpg',
        16 => 'cover-16.jpg',
        17 => 'cover-17.jpg',
        18 => 'cover-18.jpg',
        19 => 'cover-19.jpg',
        20 => 'cover-20.jpg',
        21 => 'cover-21.jpg',
        22 => 'cover-22.jpg',
        23 => 'cover-23.jpg',
        24 => 'cover-24.jpg',
        25 => 'cover-25.jpg',
        26 => 'cover-26.jpg',
        27 => 'cover-27.jpg',
        28 => 'cover-28.jpg',
        29 => 'cover-29.jpg',
    ];

    public const BOOKS = [
        'Les Misérables', 'Notre-Dame de Paris', 'L\'Étranger', 'La Peste',
        'Germinal', 'L\'Assommoir', 'Du côté de chez Swann', 'À l\'ombre des jeunes filles en fleurs',
        'Madame Bovary', 'L\'Éducation sentimentale', 'Les Trois Mousquetaires', 'Le Comte de Monte-Cristo',
        'Vingt Mille Lieues sous les mers', 'Le Tour du monde en 80 jours', 'Le Petit Prince',
        'Vol de nuit', 'Le Deuxième Sexe', 'Les Mandarins', 'L\'Amant', 'Moderato cantabile',
        'La Nausée', 'Les Mots', 'Candide', 'Zadig', 'Le Tartuffe',
        'Le Misanthrope', '1984', 'La Ferme des animaux', 'Fondation', 'Les Robots',
    ];

    public function load(ObjectManager $manager): void
    {
        $slugger = new AsciiSlugger('fr');

        foreach (self::BOOKS as $i => $title) {
            $book = new Book();
            $book->setTitle($title);
            $book->setSlug(strtolower($slugger->slug($title)) . '-' . $i);
            $book->setDescription("Description de « {$title} ». Un ouvrage incontournable de la littérature.");
            $book->setPageCount(rand(120, 800));
            $book->setTotalCopies(rand(1, 8));
            $book->setAvailableCopies(rand(0, $book->getTotalCopies()));
            $book->setPublicationDate(new \DateTimeImmutable('-' . rand(1, 150) . ' years'));

            // Author: match to approximate real authors
            $authorIndex = (int) floor($i / 2);
            if ($authorIndex >= 15) {
                $authorIndex = $i % 15;
            }
            $book->setAuthor($this->getReference('author-' . $authorIndex, Author::class));

            // Language: mostly French, some English
            $langIndex = $i >= 26 ? 1 : 0; // English for Orwell/Asimov books
            $book->setLanguage($this->getReference('language-' . $langIndex, Language::class));

            // Categories: 1 to 3 per book
            $numCategories = rand(1, 3);
            $usedCategories = [];
            for ($c = 0; $c < $numCategories; $c++) {
                $catIndex = rand(0, 7);
                if (!in_array($catIndex, $usedCategories)) {
                    $book->addCategory($this->getReference('category-' . $catIndex, Category::class));
                    $usedCategories[] = $catIndex;
                }
            }

            if (isset(self::COVERS[$i])) {
                $book->setCoverImage(self::COVERS[$i]);
            }

            $manager->persist($book);
            $this->addReference('book-' . $i, $book);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AuthorFixtures::class,
            LanguageFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
