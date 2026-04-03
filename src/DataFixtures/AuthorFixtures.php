<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AuthorFixtures extends Fixture
{
    public const AUTHORS = [
        ['firstName' => 'Victor', 'lastName' => 'Hugo'],
        ['firstName' => 'Albert', 'lastName' => 'Camus'],
        ['firstName' => 'Émile', 'lastName' => 'Zola'],
        ['firstName' => 'Marcel', 'lastName' => 'Proust'],
        ['firstName' => 'Gustave', 'lastName' => 'Flaubert'],
        ['firstName' => 'Alexandre', 'lastName' => 'Dumas'],
        ['firstName' => 'Jules', 'lastName' => 'Verne'],
        ['firstName' => 'Antoine', 'lastName' => 'de Saint-Exupéry'],
        ['firstName' => 'Simone', 'lastName' => 'de Beauvoir'],
        ['firstName' => 'Marguerite', 'lastName' => 'Duras'],
        ['firstName' => 'Jean-Paul', 'lastName' => 'Sartre'],
        ['firstName' => 'Voltaire', 'lastName' => ''],
        ['firstName' => 'Molière', 'lastName' => ''],
        ['firstName' => 'George', 'lastName' => 'Orwell'],
        ['firstName' => 'Isaac', 'lastName' => 'Asimov'],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::AUTHORS as $i => $data) {
            $author = new Author();
            $author->setFirstName($data['firstName']);
            $author->setLastName($data['lastName'] ?: $data['firstName']);
            $author->setBiography('Auteur célèbre de la littérature.');
            $manager->persist($author);
            $this->addReference('author-' . $i, $author);
        }

        $manager->flush();
    }
}
