<?php

namespace App\Tests\Entity;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Review;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
    public function testBookCreation(): void
    {
        $book = new Book();
        $book->setTitle('Les Misérables');
        $book->setTotalCopies(5);
        $book->setAvailableCopies(3);

        $this->assertSame('Les Misérables', $book->getTitle());
        $this->assertSame(5, $book->getTotalCopies());
        $this->assertSame(3, $book->getAvailableCopies());
    }

    public function testGetAverageRatingWithNoReviews(): void
    {
        $book = new Book();

        $this->assertNull($book->getAverageRating());
    }

    public function testBookDefaultCopies(): void
    {
        $book = new Book();

        $this->assertSame(1, $book->getTotalCopies());
        $this->assertSame(1, $book->getAvailableCopies());
    }

    public function testBookToString(): void
    {
        $book = new Book();
        $book->setTitle('Test Book');

        $this->assertSame('Test Book', (string) $book);
    }

    public function testBookSlug(): void
    {
        $book = new Book();
        $book->setSlug('les-miserables');

        $this->assertSame('les-miserables', $book->getSlug());
    }
}
