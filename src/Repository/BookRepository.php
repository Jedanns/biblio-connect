<?php

namespace App\Repository;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @return Book[]
     */
    public function findBySearchCriteria(?string $query, ?Category $category, ?Author $author): array
    {
        $qb = $this->createQueryBuilder('b')
            ->leftJoin('b.author', 'a')
            ->leftJoin('b.categories', 'c')
            ->addSelect('a', 'c');

        if ($query) {
            $qb->andWhere('b.title LIKE :query OR a.firstName LIKE :query OR a.lastName LIKE :query')
                ->setParameter('query', '%' . $query . '%');
        }

        if ($category) {
            $qb->andWhere(':category MEMBER OF b.categories')
                ->setParameter('category', $category);
        }

        if ($author) {
            $qb->andWhere('b.author = :author')
                ->setParameter('author', $author);
        }

        return $qb->orderBy('b.title', 'ASC')->getQuery()->getResult();
    }

    /**
     * @return Book[]
     */
    public function findLatestBooks(int $limit = 10): array
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.author', 'a')
            ->addSelect('a')
            ->orderBy('b.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
