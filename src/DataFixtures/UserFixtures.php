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
        $this->createUser($manager, 'admin@biblioconnect.fr', 'Camille', 'Fontaine', ['ROLE_ADMIN'], 'admin123', 'user-admin');

        $this->createUser($manager, 'librarian1@biblioconnect.fr', 'Nathalie', 'Berger', ['ROLE_LIBRARIAN'], 'librarian123', 'user-librarian-1');
        $this->createUser($manager, 'librarian2@biblioconnect.fr', 'Éric', 'Lefèvre', ['ROLE_LIBRARIAN'], 'librarian123', 'user-librarian-2');

        $readers = [
            ['Jean', 'Dupont'], ['Marie', 'Martin'], ['Pierre', 'Bernard'], ['Sophie', 'Petit'], ['Lucas', 'Robert'],
            ['Emma', 'Richard'], ['Thomas', 'Durand'], ['Léa', 'Moreau'], ['Hugo', 'Simon'], ['Chloé', 'Laurent'],
        ];

        foreach ($readers as $i => [$firstName, $lastName]) {
            $this->createUser($manager, 'user' . ($i + 1) . '@biblioconnect.fr', $firstName, $lastName, ['ROLE_USER'], 'user123', 'user-' . $i);
        }

        $manager->flush();
    }

    private function createUser(ObjectManager $manager, string $email, string $firstName, string $lastName, array $roles, string $plainPassword, string $reference): void
    {
        $user = new User();
        $user->setEmail($email);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setRoles($roles);
        $user->setPassword($this->hasher->hashPassword($user, $plainPassword));
        $manager->persist($user);
        $this->addReference($reference, $user);
    }
}
