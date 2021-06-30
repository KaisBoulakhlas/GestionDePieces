<?php

namespace App\DataFixtures;

use App\Entity\Estimate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EstimateFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $estimate = new Estimate();
        $estimate->setTitle('Devis n°1');
        $estimate->setDeadline(new \DateTime('05/07/2021'));
        $estimate->setCustomer($this->getReference('customer_0'));
        $estimate->setStatus(0);
        $manager->persist($estimate);

        $estimate2 = new Estimate();
        $estimate2->setTitle('Devis n°2');
        $estimate2->setDeadline(new \DateTime('08/07/2021'));
        $estimate2->setCustomer($this->getReference('customer_1'));
        $estimate2->setStatus(1);
        $manager->persist($estimate2);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CustomerFixtures::class,
        );
    }
}
