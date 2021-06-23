<?php

namespace App\DataFixtures;

use App\Entity\WorkStation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class WorkStationFixtures extends Fixture implements DependentFixtureInterface
{
    public const WORKSTATION_REFERENCE = 'workstation';
    public const WORKSTATION_REFERENCE2 = 'workstation2';
    public const WORKSTATION_REFERENCE3 = 'workstation3';
    public const WORKSTATION_REFERENCE4 = 'workstation4';

    public function load(ObjectManager $manager)
    {
        $workstation = new WorkStation();
        $workstation->setLibelle("Poste peinture");
        $workstation->addUser($this->getReference(UserFixtures::USER_REFERENCE));
        $workstation->addUser($this->getReference(UserFixtures::USER_REFERENCE2));
        $this->setReference(self::WORKSTATION_REFERENCE,$workstation);
        $manager->persist($workstation);

        $workstation2 = new WorkStation();
        $workstation2->setLibelle("Poste assemblage des pièces intermédiaires");
        $workstation2->addUser($this->getReference(UserFixtures::USER_REFERENCE));
        $workstation2->addUser($this->getReference(UserFixtures::USER_REFERENCE3));
        $this->setReference(self::WORKSTATION_REFERENCE2,$workstation2);
        $manager->persist($workstation2);

        $workstation3 = new WorkStation();
        $workstation3->setLibelle("Poste finition du produit");
        $workstation3->addUser($this->getReference(UserFixtures::USER_REFERENCE));
        $workstation3->addUser($this->getReference(UserFixtures::USER_REFERENCE2));
        $workstation3->addUser($this->getReference(UserFixtures::USER_REFERENCE3));
        $workstation3->addUser($this->getReference(UserFixtures::USER_REFERENCE4));
        $workstation3->addUser($this->getReference(UserFixtures::USER_REFERENCE5));
        $workstation3->addUser($this->getReference(UserFixtures::USER_REFERENCE6));
        $workstation3->addUser($this->getReference(UserFixtures::USER_REFERENCE7));
        $this->setReference(self::WORKSTATION_REFERENCE3,$workstation3);
        $manager->persist($workstation3);

        $workstation4 = new WorkStation();
        $workstation4->setLibelle("Poste découpage de bois");
        $workstation4->addUser($this->getReference(UserFixtures::USER_REFERENCE2));
        $workstation4->addUser($this->getReference(UserFixtures::USER_REFERENCE6));
        $workstation4->addUser($this->getReference(UserFixtures::USER_REFERENCE4));
        $this->setReference(self::WORKSTATION_REFERENCE4,$workstation4);
        $manager->persist($workstation4);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }

}
