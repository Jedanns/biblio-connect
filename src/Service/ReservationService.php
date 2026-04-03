<?php

namespace App\Service;

use App\Entity\Book;
use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ReservationService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {}

    public function createReservation(
        User $user,
        Book $book,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
    ): Reservation {
        if ($book->getAvailableCopies() <= 0) {
            throw new \LogicException('Aucun exemplaire disponible pour ce livre.');
        }

        $reservation = new Reservation();
        $reservation->setUser($user);
        $reservation->setBook($book);
        $reservation->setStartDate($startDate);
        $reservation->setEndDate($endDate);
        $reservation->setStatus(Reservation::STATUS_PENDING);

        $book->setAvailableCopies($book->getAvailableCopies() - 1);

        $this->em->persist($reservation);
        $this->em->flush();

        return $reservation;
    }

    public function cancelReservation(Reservation $reservation): void
    {
        $reservation->setStatus(Reservation::STATUS_CANCELLED);
        $book = $reservation->getBook();
        $book->setAvailableCopies($book->getAvailableCopies() + 1);

        $this->em->flush();
    }

    public function activateReservation(Reservation $reservation): void
    {
        $reservation->setStatus(Reservation::STATUS_ACTIVE);
        $this->em->flush();
    }

    public function returnBook(Reservation $reservation): void
    {
        $reservation->setStatus(Reservation::STATUS_RETURNED);
        $reservation->setReturnedAt(new \DateTimeImmutable());
        $book = $reservation->getBook();
        $book->setAvailableCopies($book->getAvailableCopies() + 1);

        $this->em->flush();
    }
}
