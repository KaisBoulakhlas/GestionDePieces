<?php

namespace App\DataFixtures;

use App\Entity\Machine;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MachineFixtures extends Fixture implements DependentFixtureInterface
{
    public const MACHINE_REFERENCE = 'machine';
    public const MACHINE_REFERENCE2 = 'machine2';
    public const MACHINE_REFERENCE3 = 'machine3';
    public const MACHINE_REFERENCE4 = 'machine4';
    public const MACHINE_REFERENCE5 = 'machine5';


    public function load(ObjectManager $manager)
    {
       $machine = new Machine();
       $machine->setLibelle("Scieuse de bois");
       $this->setReference(self::MACHINE_REFERENCE, $machine);
       $machine->setWorkStation($this->getReference(WorkStationFixtures::WORKSTATION_REFERENCE4));
       $manager->persist($machine);

       $machine2 = new Machine();
       $machine2->setLibelle("Visseuse");
       $this->setReference(self::MACHINE_REFERENCE2, $machine2);
       $machine2->setWorkStation($this->getReference(WorkStationFixtures::WORKSTATION_REFERENCE2));
       $manager->persist($machine2);

       $machine3 = new Machine();
       $machine3->setLibelle("Repassage de peinture");
       $this->setReference(self::MACHINE_REFERENCE3, $machine3);
       $machine3->setWorkStation($this->getReference(WorkStationFixtures::WORKSTATION_REFERENCE));
       $manager->persist($machine3);

       $machine4 = new Machine();
       $machine4->setLibelle("Vernisseuse");
       $this->setReference(self::MACHINE_REFERENCE4, $machine4);
       $machine4->setWorkStation($this->getReference(WorkStationFixtures::WORKSTATION_REFERENCE3));
       $manager->persist($machine4);

       $machine5 = new Machine();
       $machine5->setLibelle("Perforeuse");
       $this->setReference(self::MACHINE_REFERENCE5, $machine5);
       $machine5->setWorkStation($this->getReference(WorkStationFixtures::WORKSTATION_REFERENCE4));
       $manager->persist($machine5);

       $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            WorkStationFixtures::class
        );
    }
}
