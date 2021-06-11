<?php

namespace App\DataFixtures;

use App\Entity\Range;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RangeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $range = new Range();
        $range->setLibelle('Gamme de fabrication 1 - Table de ping-pong ');
        $manager->persist($range);

        $range2 = new Range();
        $range2->setLibelle('Gamme de fabrication 2 - Table de ping-pong ');
        $manager->persist($range2);

        $range3 = new Range();
        $range3->setLibelle('Gamme de fabrication 3 - Table de ping-pong ');
        $manager->persist($range3);

        $range4 = new Range();
        $range4->setLibelle('Gamme de fabrication 4 - Table de ping-pong ');
        $manager->persist($range4);

        $manager->flush();
    }
}
