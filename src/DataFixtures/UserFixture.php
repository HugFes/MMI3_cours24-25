<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private const DATA_SET = [
        [
            'email' => 'super_admin@example.com',
            'password' => 'super_admin',
            'roles' => ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN']
        ],
        [
            'email' => 'admin@example.com',
            'password' => 'admin',
            'roles' => ['ROLE_USER', 'ROLE_ADMIN'],
        ],
        [
            'email' => 'user@example.com',
            'password' => 'user',
            'roles' => ['ROLE_USER'],
        ],
    ];

    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {

        foreach (self::DATA_SET as $data) {
            $user = new User();
            $user
                ->setEmail($data['email'])
                ->setRoles($data['roles']);
            $user->setPassword(
                $this->userPasswordHasher->hashPassword($user, $data['password'])
            );
            $manager->persist($user);
        }

        $manager->flush();
    }
}
