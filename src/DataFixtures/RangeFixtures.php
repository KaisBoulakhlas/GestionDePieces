<?php

namespace App\DataFixtures;

use App\Entity\Range;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RangeFixtures extends Fixture implements DependentFixtureInterface
{
    public const RANGE_REFERENCE = 'range';
    public const RANGE_REFERENCE2 = 'range2';
    public const RANGE_REFERENCE3 = 'range3';
    public const RANGE_REFERENCE4 = 'range4';

    public function load(ObjectManager $manager)
    {
        $range = new Range();
        $range->setLibelle('Gamme de fabrication 1 - Table de ping-pong peinturée');
        $this->setReference(self::RANGE_REFERENCE, $range);
        $range->setUserWorkstation($this->getReference(UserFixtures::USER_REFERENCE));
        $range->setPiece($this->getReference(PiecesFixtures::PIECE_REFERENCE1));
        $manager->persist($range);

        $range2 = new Range();
        $range2->setLibelle('Gamme de fabrication 2 - Table de ping-pong sans peinture');
        $this->setReference(self::RANGE_REFERENCE2, $range2);
        $range2->setUserWorkstation($this->getReference(UserFixtures::USER_REFERENCE2));
        $range2->setPiece($this->getReference(PiecesFixtures::PIECE_REFERENCE2));
        $manager->persist($range2);

        $range3 = new Range();
        $range3->setLibelle('Gamme de fabrication 3 - Chevron');
        $this->setReference(self::RANGE_REFERENCE3, $range3);
        $range3->setUserWorkstation($this->getReference(UserFixtures::USER_REFERENCE3));
        $range3->setPiece($this->getReference(PiecesFixtures::PIECE_REFERENCE3));
        $manager->persist($range3);

        $range4 = new Range();
        $range4->setLibelle('Gamme de fabrication 4 - Traverse ');
        $this->setReference(self::RANGE_REFERENCE4, $range4);
        $range4->setUserWorkstation($this->getReference(UserFixtures::USER_REFERENCE4));
        $range4->setPiece($this->getReference(PiecesFixtures::PIECE_REFERENCE4));
        $manager->persist($range4);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            PiecesFixtures::class,
        );
    }
}
