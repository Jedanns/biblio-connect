<?php

namespace App\Tests\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    public function testBookIndexPageReturns200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/books');

        $this->assertResponseIsSuccessful();
    }

    public function testBookIndexDisplaysBooks(): void
    {
        $client = static::createClient();
        $client->request('GET', '/books');

        $this->assertSelectorExists('.book-card');
    }

    public function testBookShowPageDisplaysTitle(): void
    {
        $client = static::createClient();
        $bookRepository = static::getContainer()->get(BookRepository::class);
        $book = $bookRepository->findOneBy([]);

        $client->request('GET', '/books/' . $book->getSlug());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $book->getTitle());
    }

    public function testBookSearchWithQuery(): void
    {
        $client = static::createClient();
        $client->request('GET', '/books?book_search[query]=Misérables');

        $this->assertResponseIsSuccessful();
    }
}
