<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class BookFixtures extends Fixture implements DependentFixtureInterface
{
    public const COVERS = [
        0 => 'cover-0.jpg',  1 => 'cover-1.jpg',  2 => 'cover-2.jpg',  3 => 'cover-3.jpg',
        4 => 'cover-4.jpg',  5 => 'cover-5.jpg',  6 => 'cover-6.jpg',  7 => 'cover-7.jpg',
        8 => 'cover-8.jpg',  9 => 'cover-9.jpg',  10 => 'cover-10.jpg', 11 => 'cover-11.jpg',
        12 => 'cover-12.jpg', 13 => 'cover-13.jpg', 14 => 'cover-14.jpg', 15 => 'cover-15.jpg',
        16 => 'cover-16.jpg', 17 => 'cover-17.jpg', 18 => 'cover-18.jpg', 19 => 'cover-19.jpg',
        20 => 'cover-20.jpg', 21 => 'cover-21.jpg', 22 => 'cover-22.jpg', 23 => 'cover-23.jpg',
        24 => 'cover-24.jpg', 25 => 'cover-25.jpg', 26 => 'cover-26.jpg', 27 => 'cover-27.jpg',
        28 => 'cover-28.jpg', 29 => 'cover-29.jpg',
    ];

    /** [title, author_index, lang_index (0=fr, 1=en), synopsis] */
    public const CATALOG = [
        ['Les Misérables', 0, 0, 'Jean Valjean, ancien forçat, tente de se racheter dans la France du XIXe siècle. Fresque sociale sur la misère, la justice et la rédemption.'],
        ['Notre-Dame de Paris', 0, 0, 'Le bossu Quasimodo, la bohémienne Esmeralda et l\'archidiacre Frollo dans le Paris médiéval. Roman fondateur du patrimoine gothique.'],
        ['L\'Étranger', 1, 0, 'Meursault, narrateur indifférent, commet un meurtre absurde sur une plage d\'Alger. Premier volet du cycle de l\'absurde de Camus.'],
        ['La Peste', 1, 0, 'Oran, années 1940. Une épidémie de peste isole la ville. Chronique allégorique sur la résistance humaine face au mal.'],
        ['Germinal', 2, 0, 'Étienne Lantier descend dans les mines du Nord. Grève, misère et révolte ouvrière dans le treizième tome des Rougon-Macquart.'],
        ['L\'Assommoir', 2, 0, 'Gervaise Macquart rêve d\'une blanchisserie à Paris. L\'alcoolisme et la misère détruiront ses ambitions. Septième tome de la saga.'],
        ['Du côté de chez Swann', 3, 0, 'Première partie de la Recherche. La madeleine, Combray, l\'amour de Swann pour Odette : naissance d\'une cathédrale littéraire.'],
        ['À l\'ombre des jeunes filles en fleurs', 3, 0, 'Le narrateur découvre Balbec, la mer et le groupe de jeunes filles. Prix Goncourt 1919, pivot de la Recherche.'],
        ['Madame Bovary', 4, 0, 'Emma Bovary, mariée à un médecin médiocre, cherche l\'évasion dans l\'adultère et le luxe. Autopsie du bovarysme et du désenchantement.'],
        ['L\'Éducation sentimentale', 4, 0, 'Frédéric Moreau poursuit l\'amour impossible de Mme Arnoux dans le Paris de 1848. Roman de la désillusion et de la velléité.'],
        ['Les Trois Mousquetaires', 5, 0, 'D\'Artagnan rejoint Athos, Porthos et Aramis dans les intrigues de la cour de Louis XIII. Roman de cape et d\'épée fondateur du genre.'],
        ['Le Comte de Monte-Cristo', 5, 0, 'Edmond Dantès, trahi et emprisonné, s\'évade et orchestre une vengeance méthodique. Le plus grand roman d\'aventure français.'],
        ['Vingt Mille Lieues sous les mers', 6, 0, 'Le capitaine Nemo parcourt les océans à bord du Nautilus. Voyage visionnaire entre exploration scientifique et misanthropie.'],
        ['Le Tour du monde en 80 jours', 6, 0, 'Phileas Fogg parie qu\'il bouclera le tour du globe en 80 jours. Course contre la montre à travers trains, bateaux et éléphants.'],
        ['Le Petit Prince', 7, 0, 'Un aviateur échoué dans le Sahara rencontre un enfant venu d\'un astéroïde. Conte philosophique sur l\'essentiel invisible pour les yeux.'],
        ['Vol de nuit', 7, 0, 'Rivière dirige les vols postaux nocturnes en Argentine. Récit sur le sacrifice individuel au service d\'une mission qui dépasse l\'homme.'],
        ['Le Deuxième Sexe', 8, 0, 'Essai fondateur du féminisme moderne. « On ne naît pas femme, on le devient » : analyse des structures d\'oppression genrée.'],
        ['Les Mandarins', 8, 0, 'Les intellectuels parisiens de l\'après-guerre entre engagement politique et vie sentimentale. Prix Goncourt 1954.'],
        ['L\'Amant', 9, 0, 'Saïgon, années 1930. Une jeune Française entame une liaison avec un riche Chinois. Autobiographie fragmentée, Prix Goncourt 1984.'],
        ['Moderato cantabile', 9, 0, 'Anne Desbaresdes et Chauvin reconstruisent un crime passionnel dans un café de province. Écriture blanche et tension sourde.'],
        ['La Nausée', 10, 0, 'Antoine Roquentin, à Bouville, découvre l\'absurdité de l\'existence. Journal intime philosophique, manifeste de l\'existentialisme.'],
        ['Les Mots', 10, 0, 'Autobiographie intellectuelle de Sartre : comment un enfant bourgeois est devenu écrivain par nécessité. Publié l\'année du Nobel refusé.'],
        ['Candide', 11, 0, 'Candide, chassé du château de Thunder-ten-tronckh, traverse guerres et catastrophes. Satire de l\'optimisme leibnizien par Voltaire.'],
        ['Zadig', 11, 0, 'Zadig, jeune Babylonien sage, affronte l\'injustice et le hasard. Conte oriental philosophique sur la destinée et la providence.'],
        ['Le Tartuffe', 12, 0, 'Orgon accueille Tartuffe, faux dévot qui convoite sa femme et sa fortune. Comédie féroce sur l\'hypocrisie religieuse, censurée cinq ans.'],
        ['Le Misanthrope', 12, 0, 'Alceste, incapable de supporter la fausseté mondaine, aime pourtant la coquette Célimène. Comédie amère sur la sincérité impossible.'],
        ['1984', 13, 1, 'Winston Smith, fonctionnaire d\'Oceania, tente de résister à Big Brother et à la novlangue. Dystopie totalitaire devenue référence universelle.'],
        ['La Ferme des animaux', 13, 1, 'Les animaux d\'une ferme renversent leur maître humain. La révolution dégénère en tyrannie porcine. Fable satirique sur le stalinisme.'],
        ['Fondation', 14, 1, 'Hari Seldon prédit la chute de l\'Empire Galactique et crée une Fondation pour raccourcir l\'âge sombre. Space opera intellectuel sur l\'histoire cyclique.'],
        ['Les Robots', 14, 1, 'Neuf nouvelles explorant les trois lois de la robotique et leurs paradoxes. Texte fondateur de la science-fiction sur l\'intelligence artificielle.'],
    ];

    public function load(ObjectManager $manager): void
    {
        $slugger = new AsciiSlugger('fr');

        foreach (self::CATALOG as $i => [$title, $authorIdx, $langIdx, $synopsis]) {
            $book = new Book();
            $book->setTitle($title);
            $book->setSlug(strtolower($slugger->slug($title)) . '-' . $i);
            $book->setDescription($synopsis);
            $book->setPageCount(rand(120, 800));
            $book->setTotalCopies(rand(1, 8));
            $book->setAvailableCopies(rand(0, $book->getTotalCopies()));
            $book->setPublicationDate(new \DateTimeImmutable('-' . rand(1, 150) . ' years'));
            $book->setAuthor($this->getReference('author-' . $authorIdx, Author::class));
            $book->setLanguage($this->getReference('language-' . $langIdx, Language::class));

            $categoryCount = rand(1, 3);
            $assigned = [];
            for ($c = 0; $c < $categoryCount; $c++) {
                $catIdx = rand(0, 7);
                if (!in_array($catIdx, $assigned)) {
                    $book->addCategory($this->getReference('category-' . $catIdx, Category::class));
                    $assigned[] = $catIdx;
                }
            }

            if (isset(self::COVERS[$i])) {
                $book->setCoverImage(self::COVERS[$i]);
            }

            $manager->persist($book);
            $this->addReference('book-' . $i, $book);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AuthorFixtures::class,
            LanguageFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
