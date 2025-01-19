<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\VideoGame;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class VideoGameFixture extends Fixture implements DependentFixtureInterface
{
    const DATA_SET = [
        [
            'name' => 'CS:GO',
            'difficulty' => 3,
            'synopsis' => null,
            'categories' => ['FPS', 'Action'],
        ],
        [
            'name' => 'Borderlands',
            'difficulty' => 3,
            'synopsis' => null,
            'categories' => ['FPS', 'Adv.'],
        ],
        [
            'name' => 'Mario Sunshine',
            'difficulty' => 3,
            'synopsis' => null,
            'categories' => ['Adv.'],
        ],
    ];

    public function __construct(
        private readonly CategoryRepository $categoryRepository
    )
    {

    }

    public function load(ObjectManager $manager): void
    {

        // DONNÉES STATIQUES
        foreach (self::DATA_SET as $data) {
            $game = new VideoGame();
            $game->setName($data['name'])
                ->setDifficulty($data['difficulty'])
                ->setSynopsis($data['synopsis']);

            // Résolution des catégories
            foreach ($data['categories'] as $category) {
                $game->addCategory(
                    $this->getReference(CategoryFixture::getReferenceKey($category), Category::class)
                );
            }

            $manager->persist($game);
        }

        // DONNÉES ALÉATOIRE
        $faker = \Faker\Factory::create('fr_FR');
        $categories = $this->categoryRepository->findAll();

        for ($i = 0; $i < 3; $i++) {
            $game = new VideoGame();
            $game->setName($faker->unique()->word())
                ->setDifficulty($faker->numberBetween(0, 5))
                ->setSynopsis($faker->optional()->text());

            // Catégories aléatoires
            $game->addCategory(
                $faker->randomElement($categories)
            );

            $manager->persist($game);
        }


        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixture::class,
        ];
    }
}
