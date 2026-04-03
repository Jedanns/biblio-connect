<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Review;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    private const COMMENTS = [
        'Excellent ouvrage, je le recommande vivement !',
        'Un classique incontournable de la littérature.',
        'Très bien écrit, une lecture passionnante.',
        'J\'ai beaucoup aimé l\'histoire et les personnages.',
        'Un peu long par moments, mais globalement très bon.',
        'Pas mal, mais j\'ai préféré d\'autres oeuvres de cet auteur.',
        'Lecture agréable, style fluide.',
        'Un chef-d\'oeuvre absolu.',
        'Intéressant mais parfois difficile à suivre.',
        'Une belle découverte !',
        null, null, null, null, null,
    ];

    public function load(ObjectManager $manager): void
    {
        $usedPairs = [];

        for ($i = 0; $i < 40; $i++) {
            $userIndex = $i % 10;
            $bookIndex = (int) floor($i / 2) % 30;

            $pairKey = $userIndex . '-' . $bookIndex;
            if (in_array($pairKey, $usedPairs)) {
                $bookIndex = ($bookIndex + 7) % 30;
                $pairKey = $userIndex . '-' . $bookIndex;
                if (in_array($pairKey, $usedPairs)) {
                    continue;
                }
            }
            $usedPairs[] = $pairKey;

            $review = new Review();
            $review->setUser($this->getReference('user-' . $userIndex, User::class));
            $review->setBook($this->getReference('book-' . $bookIndex, Book::class));
            $review->setRating(rand(2, 5));
            $review->setComment(self::COMMENTS[array_rand(self::COMMENTS)]);
            $review->setIsApproved($i < 30);

            $manager->persist($review);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            BookFixtures::class,
        ];
    }
}
