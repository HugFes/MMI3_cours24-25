<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{

    const DATA_SET = [
        [
            'name' => 'Tire à la première personne',
            'short_name' => 'FPS',
        ],
        [
            'name' => 'Aventure',
            'short_name' => 'Adv.',
        ],
        [
            'name' => 'Action',
            'short_name' => 'Action',
        ],
    ];


    public function load(ObjectManager $manager): void
    {

        // Données statiques
        foreach (self::DATA_SET as $data) {
            $cat = new Category();
            $cat
                ->setName($data['name'])
                ->setShortName($data['short_name']);

            // Sauvegarde de la reférence pour l'associer facielemnt à un jeu vidéo
            $this->addReference(self::getReferenceKey($data['short_name']), $cat);

            $manager->persist($cat);
        }


        // Données aléatoires
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 3; $i++) {
            $category = new Category();
            $category
                ->setName($faker->words(3, true))
                ->setShortName($faker->word());

            $manager->persist($category);
        }

        $manager->flush();
    }


    public static function getReferenceKey(string $categoryShortName): string
    {
        return 'category_' . $categoryShortName;
    }
}
