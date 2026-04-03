<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Favorite;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Favorite>
 */
class FavoriteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favorite::class);
    }

    public function findByUserAndBook(User $user, Book $book): ?Favorite
    {
        return $this->findOneBy(['user' => $user, 'book' => $book]);
    }

    /**
     * @return Favorite[]
     */
    public function findByUserWithBooks(User $user): array
    {
        return $this->createQueryBuilder('f')
            ->join('f.book', 'b')
            ->join('b.author', 'a')
            ->addSelect('b', 'a')
            ->where('f.user = :user')
            ->setParameter('user', $user)
            ->orderBy('f.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
