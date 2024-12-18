<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Test;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TestFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $tests = [
            [
                'name' => 'PHP Programming Test',
                'slug' => 'php-programming-test',
                'category' => 'programming',
                'reference' => 'user_1',
            ],
            [
                'name' => 'UI Design Basics',
                'slug' => 'ui-design-basics',
                'category' => 'design',
                'reference' => 'user_2',
            ],
            [
                'name' => 'Marketing Strategy',
                'slug' => 'marketing-strategy',
                'category' => 'marketing',
                'reference' => 'user_2',
            ],
            [
                'name' => 'JavaScript Fundamentals',
                'slug' => 'javascript-fundamentals',
                'category' => 'programming',
                'reference' => 'user_1',
            ],
            [
                'name' => 'UX Research Methods',
                'slug' => 'ux-research-methods',
                'category' => 'design',
                'reference' => 'user_2',
            ],
        ];

        foreach ($tests as $testData) {
            $test = new Test();
            $test->setName($testData['name']);
            $test->setSlug($testData['slug']);
            $test->setCategory($this->getReference('category_' . $testData['category'], Category::class));
            $test->setCreatedBy($this->getReference($testData['reference'], User::class));

            $manager->persist($test);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}