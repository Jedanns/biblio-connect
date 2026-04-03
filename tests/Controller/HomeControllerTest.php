<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testHomepageReturns200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }

    public function testHomepageContainsBiblioConnect(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSelectorTextContains('h1', 'BiblioConnect');
    }

    public function testHomepageHasNavigationLinks(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSelectorExists('a[href="/books"]');
    }
}
