<?php

namespace App\DataFixtures;

use App\Entity\WorkStation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class WorkStationFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $workstation = new WorkStation();
        $workstation->setLibelle("Poste découpage de bois");
        $manager->persist($workstation);

        $workstation2 = new WorkStation();
        $workstation2->setLibelle("Poste assemblage des pièces intermédiaires");
        $manager->persist($workstation2);

        $workstation3 = new WorkStation();
        $workstation3->setLibelle("Poste finition du produit");
        $manager->persist($workstation3);

        $workstation4 = new WorkStation();
        $workstation4->setLibelle("Poste découpage de bois");
        $manager->persist($workstation4);

        $manager->flush();
    }

}
