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
    private const DISTRIBUTION = [
        Reservation::STATUS_PENDING   => 5,
        Reservation::STATUS_ACTIVE    => 10,
        Reservation::STATUS_RETURNED  => 8,
        Reservation::STATUS_CANCELLED => 2,
    ];

    public function load(ObjectManager $manager): void
    {
        $i = 0;

        foreach (self::DISTRIBUTION as $status => $count) {
            for ($n = 0; $n < $count; $n++, $i++) {
                $reservation = new Reservation();
                $reservation->setUser($this->getReference('user-' . ($i % 10), User::class));
                $reservation->setBook($this->getReference('book-' . ($i % 30), Book::class));
                $reservation->setStatus($status);

                [$startDate, $endDate] = $this->generateDates($status);
                $reservation->setStartDate($startDate);
                $reservation->setEndDate($endDate);

                if ($status === Reservation::STATUS_RETURNED) {
                    $reservation->setReturnedAt($endDate->modify('+' . rand(0, 5) . ' days'));
                }

                $manager->persist($reservation);
            }
        }

        $manager->flush();
    }

    /** @return \DateTimeImmutable[] */
    private function generateDates(string $status): array
    {
        if ($status === Reservation::STATUS_RETURNED) {
            $start = new \DateTimeImmutable('-' . rand(30, 90) . ' days');
            return [$start, $start->modify('+14 days')];
        }

        if ($status === Reservation::STATUS_CANCELLED) {
            $start = new \DateTimeImmutable('+' . rand(5, 20) . ' days');
            return [$start, $start->modify('+14 days')];
        }

        $start = new \DateTimeImmutable('+' . rand(1, 30) . ' days');
        return [$start, $start->modify('+' . rand(7, 30) . ' days')];
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class, BookFixtures::class];
    }
}
