<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
class CategoryFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $Category = new Category();
        $Category->setName("Camping")
                ->setDescription("Camping is Fun FUN camping camping is fun fun fun funfufun")
                ->setThreads("camps");
                $manager->persist($Category);
        $manager->flush();
    }
}
