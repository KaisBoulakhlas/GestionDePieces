<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\WorkStation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

         $ouvrier = new User();
         $ouvrier->setEmail("kaisboulakhlas9@gmail.com");
         $ouvrier->setPassword($this->encoder->hashPassword($ouvrier, 'azerty1999'));
         $ouvrier->setUsername("Kais");
         $ouvrier->setRoles(['ROLE_OUVRIER']);
         $manager->persist($ouvrier);

         $ouvrier2 = new User();
         $ouvrier2->setEmail("yoanboulakhlas9@gmail.com");
         $ouvrier2->setPassword($this->encoder->hashPassword($ouvrier2, 'azerty'));
         $ouvrier2->setUsername("Yoan");
         $ouvrier2->setRoles(['ROLE_OUVRIER']);
         $manager->persist($ouvrier2);

         $ouvrier3 = new User();
         $ouvrier3->setEmail("said@gmail.com");
         $ouvrier3->setPassword($this->encoder->hashPassword($ouvrier3, 'azezer'));
         $ouvrier3->setUsername("Said");
         $ouvrier3->setRoles(['ROLE_OUVRIER']);
         $manager->persist($ouvrier3);

         $commercial = new User();
         $commercial->setEmail("lorisquetglas@gmail.com");
         $commercial->setPassword($this->encoder->hashPassword($commercial, 'azerty123'));
         $commercial->setUsername("Loris");
         $commercial->setRoles(['ROLE_COMMERCIAL']);
         $manager->persist($commercial);

         $manager->flush();
    }
}
