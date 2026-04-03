<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AuthorFixtures extends Fixture
{
    public const AUTHORS = [
        ['firstName' => 'Victor', 'lastName' => 'Hugo', 'bio' => 'Figure majeure du romantisme français, auteur prolifique de romans, poésie et théâtre. Pair de France, sénateur, exilé politique sous le Second Empire.'],
        ['firstName' => 'Albert', 'lastName' => 'Camus', 'bio' => 'Écrivain et philosophe franco-algérien, prix Nobel de littérature 1957. Penseur de l\'absurde et de la révolte, journaliste engagé à Combat.'],
        ['firstName' => 'Émile', 'lastName' => 'Zola', 'bio' => 'Chef de file du naturalisme, créateur de la fresque des Rougon-Macquart. Intellectuel engagé, auteur du célèbre « J\'accuse…! » dans l\'affaire Dreyfus.'],
        ['firstName' => 'Marcel', 'lastName' => 'Proust', 'bio' => 'Romancier dont l\'œuvre monumentale « À la recherche du temps perdu » a redéfini le roman moderne par l\'introspection et la mémoire involontaire.'],
        ['firstName' => 'Gustave', 'lastName' => 'Flaubert', 'bio' => 'Styliste obsessionnel, précurseur du réalisme. A passé cinq ans à écrire Madame Bovary, poursuivi pour outrage aux mœurs à sa publication.'],
        ['firstName' => 'Alexandre', 'lastName' => 'Dumas', 'bio' => 'Maître du roman historique et du feuilleton. Productivité légendaire avec plus de 600 ouvrages, souvent aidé de collaborateurs dont Auguste Maquet.'],
        ['firstName' => 'Jules', 'lastName' => 'Verne', 'bio' => 'Pionnier de la science-fiction, ses « Voyages extraordinaires » ont anticipé sous-marins, hélicoptères et voyages spatiaux. Deuxième auteur le plus traduit au monde.'],
        ['firstName' => 'Antoine', 'lastName' => 'de Saint-Exupéry', 'bio' => 'Aviateur et écrivain, disparu en mission au-dessus de la Méditerranée en 1944. Le Petit Prince est le livre le plus traduit après la Bible.'],
        ['firstName' => 'Simone', 'lastName' => 'de Beauvoir', 'bio' => 'Philosophe existentialiste et féministe. « Le Deuxième Sexe » (1949) est considéré comme l\'acte fondateur du féminisme contemporain.'],
        ['firstName' => 'Marguerite', 'lastName' => 'Duras', 'bio' => 'Romancière et cinéaste, figure du Nouveau Roman. Prix Goncourt 1984 pour « L\'Amant », récit autobiographique sur l\'Indochine.'],
        ['firstName' => 'Jean-Paul', 'lastName' => 'Sartre', 'bio' => 'Philosophe existentialiste, romancier et dramaturge. A refusé le prix Nobel de littérature en 1964 par cohérence intellectuelle.'],
        ['firstName' => 'Voltaire', 'lastName' => 'Voltaire', 'bio' => 'Philosophe des Lumières, polémiste infatigable. « Candide » reste une satire mordante de l\'optimisme leibnizien et de l\'intolérance.'],
        ['firstName' => 'Molière', 'lastName' => 'Molière', 'bio' => 'Dramaturge et comédien, fondateur de la comédie moderne française. Mort sur scène en jouant Le Malade imaginaire en 1673.'],
        ['firstName' => 'George', 'lastName' => 'Orwell', 'bio' => 'Essayiste et romancier anglais, combattant de la guerre d\'Espagne. Ses dystopies « 1984 » et « Animal Farm » sont des classiques de la littérature politique.'],
        ['firstName' => 'Isaac', 'lastName' => 'Asimov', 'bio' => 'Biochimiste et auteur américain d\'origine russe. Plus de 500 ouvrages publiés, créateur des lois de la robotique et du cycle de Fondation.'],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::AUTHORS as $i => $data) {
            $author = new Author();
            $author->setFirstName($data['firstName']);
            $author->setLastName($data['lastName']);
            $author->setBiography($data['bio']);
            $manager->persist($author);
            $this->addReference('author-' . $i, $author);
        }

        $manager->flush();
    }
}
