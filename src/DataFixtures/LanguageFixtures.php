<?php

namespace App\DataFixtures;

use App\Entity\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LanguageFixtures extends Fixture
{
    public const LANGUAGES = [
        ['name' => 'Français', 'code' => 'fr'],
        ['name' => 'English', 'code' => 'en'],
        ['name' => 'Español', 'code' => 'es'],
        ['name' => 'Deutsch', 'code' => 'de'],
        ['name' => 'Italiano', 'code' => 'it'],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::LANGUAGES as $i => $data) {
            $language = new Language();
            $language->setName($data['name']);
            $language->setCode($data['code']);
            $manager->persist($language);
            $this->addReference('language-' . $i, $language);
        }

        $manager->flush();
    }
}
