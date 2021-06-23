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

    public const USER_REFERENCE = 'user-work';
    public const USER_REFERENCE2 = 'user-work2';
    public const USER_REFERENCE3 = 'user-work3';
    public const USER_REFERENCE4 = 'user-work4';
    public const USER_REFERENCE5 = 'user-work5';
    public const USER_REFERENCE6 = 'user-work6';
    public const USER_REFERENCE7 = 'user-work7';

    public function load(ObjectManager $manager)
    {

         $ouvrier = new User();
         $ouvrier->setEmail("kaisboulakhlas9@gmail.com");
         $ouvrier->setPassword($this->encoder->hashPassword($ouvrier, 'azerty1999'));
         $ouvrier->setUsername("Kais");
         $ouvrier->setRoles(['ROLE_OUVRIER']);
         $this->setReference(self::USER_REFERENCE, $ouvrier);
         $manager->persist($ouvrier);

         $ouvrier2 = new User();
         $ouvrier2->setEmail("yoanboulakhlas9@gmail.com");
         $ouvrier2->setPassword($this->encoder->hashPassword($ouvrier2, 'azerty'));
         $ouvrier2->setUsername("Yoan");
         $ouvrier2->setRoles(['ROLE_OUVRIER']);
         $this->setReference(self::USER_REFERENCE2, $ouvrier2);
         $manager->persist($ouvrier2);

        $ouvrier3 = new User();
        $ouvrier3->setEmail("saidwaskar@gmail.com");
        $ouvrier3->setPassword($this->encoder->hashPassword($ouvrier3, 'azerty987'));
        $ouvrier3->setUsername("Said");
        $ouvrier3->setRoles(['ROLE_OUVRIER']);
        $this->setReference(self::USER_REFERENCE3, $ouvrier3);
        $manager->persist($ouvrier3);

        $ouvrier4 = new User();
        $ouvrier4->setEmail("test@gmail.com");
        $ouvrier4->setPassword($this->encoder->hashPassword($ouvrier4, 'azerty321'));
        $ouvrier4->setUsername("Test");
        $ouvrier4->setRoles(['ROLE_OUVRIER']);
        $this->setReference(self::USER_REFERENCE4, $ouvrier4);
        $manager->persist($ouvrier4);

        $ouvrier5 = new User();
        $ouvrier5->setEmail("eric@gmail.com");
        $ouvrier5->setPassword($this->encoder->hashPassword($ouvrier5, 'azerty5432'));
        $ouvrier5->setUsername("Eric");
        $ouvrier5->setRoles(['ROLE_OUVRIER']);
        $this->setReference(self::USER_REFERENCE5, $ouvrier5);
        $manager->persist($ouvrier5);

        $ouvrier6 = new User();
        $ouvrier6->setEmail("marc@gmail.com");
        $ouvrier6->setPassword($this->encoder->hashPassword($ouvrier6, 'azerty87867'));
        $ouvrier6->setUsername("Marc");
        $ouvrier6->setRoles(['ROLE_OUVRIER']);
        $this->setReference(self::USER_REFERENCE6, $ouvrier6);
        $manager->persist($ouvrier6);


        $ouvrier7 = new User();
        $ouvrier7->setEmail("test2@gmail.com");
        $ouvrier7->setPassword($this->encoder->hashPassword($ouvrier7, 'azezer'));
        $ouvrier7->setUsername("Test2");
        $ouvrier7->setRoles(['ROLE_OUVRIER']);
        $this->setReference(self::USER_REFERENCE7, $ouvrier7);
        $manager->persist($ouvrier7);

         $commercial = new User();
         $commercial->setEmail("lorisquetglas@gmail.com");
         $commercial->setPassword($this->encoder->hashPassword($commercial, 'azerty123'));
         $commercial->setUsername("Loris");
         $commercial->setRoles(['ROLE_COMMERCIAL']);
         $manager->persist($commercial);

         $manager->flush();
    }
}
