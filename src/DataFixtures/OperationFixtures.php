<?php

namespace App\DataFixtures;

use App\Entity\Machine;
use App\Entity\Operation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class OperationFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
         $operation = new Operation();
         $operation->addRange($this->getReference(RangeFixtures::RANGE_REFERENCE));
         $operation->setLibelle("Scier une planche de bois");
         $operation->setTime(new \DateTime("00:30:00"));
         $operation->setMachine($this->getReference(MachineFixtures::MACHINE_REFERENCE));
         $operation->setWorkStation($this->getReference(WorkStationFixtures::WORKSTATION_REFERENCE4));
         $manager->persist($operation);

         $operation2 = new Operation();
         $operation2->addRange($this->getReference(RangeFixtures::RANGE_REFERENCE2));
         $operation2->setLibelle("Visser les chevrons");
         $operation2->setTime(new \DateTime("00:15:00"));
         $operation2->setMachine($this->getReference(MachineFixtures::MACHINE_REFERENCE2));
         $operation2->setWorkStation($this->getReference(WorkStationFixtures::WORKSTATION_REFERENCE2));
         $manager->persist($operation2);

         $manager->flush();


    }
    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            RangeFixtures::class,
            WorkStationFixtures::class,
            MachineFixtures::class,
        );
    }

}
