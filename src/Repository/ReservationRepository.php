<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * @return Reservation[]
     */
    public function findByUserWithBooks(User $user): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.book', 'b')
            ->addSelect('b')
            ->where('r.user = :user')
            ->setParameter('user', $user)
            ->orderBy('r.reservedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Reservation[]
     */
    public function findPendingReservations(): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.book', 'b')
            ->join('r.user', 'u')
            ->addSelect('b', 'u')
            ->where('r.status = :status')
            ->setParameter('status', Reservation::STATUS_PENDING)
            ->orderBy('r.reservedAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Reservation[]
     */
    public function findAllWithRelations(): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.book', 'b')
            ->join('r.user', 'u')
            ->addSelect('b', 'u')
            ->orderBy('r.reservedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
