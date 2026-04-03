<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Review;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function findByUserAndBook(User $user, Book $book): ?Review
    {
        return $this->findOneBy(['user' => $user, 'book' => $book]);
    }

    /**
     * @return Review[]
     */
    public function findUnapprovedReviews(): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.user', 'u')
            ->join('r.book', 'b')
            ->addSelect('u', 'b')
            ->where('r.isApproved = false')
            ->orderBy('r.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
