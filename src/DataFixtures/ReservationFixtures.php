<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReservationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $statuses = [
            Reservation::STATUS_PENDING,
            Reservation::STATUS_PENDING,
            Reservation::STATUS_PENDING,
            Reservation::STATUS_PENDING,
            Reservation::STATUS_PENDING,
            Reservation::STATUS_ACTIVE,
            Reservation::STATUS_ACTIVE,
            Reservation::STATUS_ACTIVE,
            Reservation::STATUS_ACTIVE,
            Reservation::STATUS_ACTIVE,
            Reservation::STATUS_ACTIVE,
            Reservation::STATUS_ACTIVE,
            Reservation::STATUS_ACTIVE,
            Reservation::STATUS_ACTIVE,
            Reservation::STATUS_ACTIVE,
            Reservation::STATUS_RETURNED,
            Reservation::STATUS_RETURNED,
            Reservation::STATUS_RETURNED,
            Reservation::STATUS_RETURNED,
            Reservation::STATUS_RETURNED,
            Reservation::STATUS_RETURNED,
            Reservation::STATUS_RETURNED,
            Reservation::STATUS_RETURNED,
            Reservation::STATUS_CANCELLED,
            Reservation::STATUS_CANCELLED,
        ];

        foreach ($statuses as $i => $status) {
            $reservation = new Reservation();
            $reservation->setUser($this->getReference('user-' . ($i % 10), User::class));
            $reservation->setBook($this->getReference('book-' . ($i % 30), Book::class));
            $reservation->setStatus($status);

            $startOffset = rand(1, 30);
            $startDate = new \DateTimeImmutable("+{$startOffset} days");
            $endDate = $startDate->modify('+' . rand(7, 30) . ' days');

            if ($status === Reservation::STATUS_RETURNED) {
                $startDate = new \DateTimeImmutable('-' . rand(30, 90) . ' days');
                $endDate = $startDate->modify('+14 days');
                $reservation->setReturnedAt($endDate->modify('+' . rand(0, 5) . ' days'));
            } elseif ($status === Reservation::STATUS_CANCELLED) {
                $startDate = new \DateTimeImmutable('+' . rand(5, 20) . ' days');
                $endDate = $startDate->modify('+14 days');
            }

            $reservation->setStartDate($startDate);
            $reservation->setEndDate($endDate);

            $manager->persist($reservation);
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
