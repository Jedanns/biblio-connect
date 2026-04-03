<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserCreation(): void
    {
        $user = new User();
        $user->setEmail('test@test.com');
        $user->setFirstName('Jean');
        $user->setLastName('Dupont');

        $this->assertSame('test@test.com', $user->getEmail());
        $this->assertSame('Jean', $user->getFirstName());
        $this->assertSame('Dupont', $user->getLastName());
        $this->assertSame('Jean Dupont', $user->getFullName());
    }

    public function testUserAlwaysHasRoleUser(): void
    {
        $user = new User();
        $user->setRoles([]);

        $this->assertContains('ROLE_USER', $user->getRoles());
    }

    public function testUserRoles(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);

        $this->assertContains('ROLE_ADMIN', $user->getRoles());
        $this->assertContains('ROLE_USER', $user->getRoles());
    }

    public function testUserIdentifier(): void
    {
        $user = new User();
        $user->setEmail('admin@test.com');

        $this->assertSame('admin@test.com', $user->getUserIdentifier());
    }

    public function testUserIsActiveByDefault(): void
    {
        $user = new User();

        $this->assertTrue($user->isActive());
    }
}
