<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Favorite;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FavoriteFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($userIndex = 0; $userIndex < 10; $userIndex++) {
            for ($j = 0; $j < 3; $j++) {
                $bookIndex = ($userIndex * 3 + $j) % 30;

                $favorite = new Favorite();
                $favorite->setUser($this->getReference('user-' . $userIndex, User::class));
                $favorite->setBook($this->getReference('book-' . $bookIndex, Book::class));

                $manager->persist($favorite);
            }
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
