<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Admin
        $admin = new User();
        $admin->setEmail('admin@biblioconnect.fr');
        $admin->setFirstName('Admin');
        $admin->setLastName('BiblioConnect');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);
        $this->addReference('user-admin', $admin);

        // Librarians
        for ($i = 1; $i <= 2; $i++) {
            $librarian = new User();
            $librarian->setEmail("librarian{$i}@biblioconnect.fr");
            $librarian->setFirstName('Bibliothécaire');
            $librarian->setLastName("Numéro {$i}");
            $librarian->setRoles(['ROLE_LIBRARIAN']);
            $librarian->setPassword($this->hasher->hashPassword($librarian, 'librarian123'));
            $manager->persist($librarian);
            $this->addReference('user-librarian-' . $i, $librarian);
        }

        // Regular users
        $firstNames = ['Jean', 'Marie', 'Pierre', 'Sophie', 'Lucas', 'Emma', 'Thomas', 'Léa', 'Hugo', 'Chloé'];
        $lastNames = ['Dupont', 'Martin', 'Bernard', 'Petit', 'Robert', 'Richard', 'Durand', 'Moreau', 'Simon', 'Laurent'];

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail('user' . ($i + 1) . '@biblioconnect.fr');
            $user->setFirstName($firstNames[$i]);
            $user->setLastName($lastNames[$i]);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->hasher->hashPassword($user, 'user123'));
            $manager->persist($user);
            $this->addReference('user-' . $i, $user);
        }

        $manager->flush();
    }
}
