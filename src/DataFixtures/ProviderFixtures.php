<?php

namespace App\DataFixtures;

use App\Entity\Provider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProviderFixtures extends Fixture
{
    public const PROVIDER_REFERENCE = 'provider';
    public const PROVIDER_REFERENCE2 = 'provider2';

    public function load(ObjectManager $manager)
    {
        $asas = new Provider();
        $asas->setLibelle('ASAP SAS');
        $asas->setAdresse('Via Imbiani, 2, 40017 San Giovanni in Persiceto BO, Italy');
        $asas->setCity('Via Imbiani');
        $asas->setCountry('Italy');
        $this->setReference(self::PROVIDER_REFERENCE, $asas);
        $manager->persist($asas);

        $saica = new Provider();
        $saica->setLibelle('SAICA PACK');
        $saica->setAdresse('33 Rue François Rochaix, 01100 Oyonnax');
        $saica->setCity('Oyonnax');
        $saica->setCountry('France');
        $this->setReference(self::PROVIDER_REFERENCE2, $saica);
        $manager->persist($saica);

        $manager->flush();
    }
}
