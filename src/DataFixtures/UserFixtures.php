<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Create admin user
        $admin = new User();
        $admin->setUsername('Admin');
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, '123456')
        );
        $manager->persist($admin);

        // Create first regular user
        $user1 = new User();
        $user1->setUsername('User1');
        $user1->setEmail('user1@example.com');
        $user1->setRoles(['ROLE_USER']);
        $user1->setPassword(
            $this->passwordHasher->hashPassword($user1, '123456')
        );

        $this->addReference('user_1', $user1);

        $manager->persist($user1);

        // Create second regular user
        $user2 = new User();
        $user2->setUsername('User2');
        $user2->setEmail('user2@example.com');
        $user2->setRoles(['ROLE_USER']);
        $user2->setPassword(
            $this->passwordHasher->hashPassword($user2, '123456')
        );

        $this->addReference('user_2', $user2);
        
        $manager->persist($user2);

        $manager->flush();
    }
}