<?php

namespace App\DataFixtures;

use App\Entity\Piece;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PiecesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $bois = new Piece();
        $bois->setLibelle('Bois');
        $bois->setPriceCatalogue('50.00');
        $bois->setType('Matière première');
        $bois->setProvider($this->getReference(ProviderFixtures::PROVIDER_REFERENCE));
        $bois->setQuantity(5);
        $manager->persist($bois);

        $colle = new Piece();
        $colle->setLibelle('Colle');
        $colle->setPriceCatalogue('50.00');
        $colle->setQuantity(5);
        $colle->setType('Matière première');
        $colle->setProvider($this->getReference(ProviderFixtures::PROVIDER_REFERENCE));
        $manager->persist($colle);

        $caoutchouc = new Piece();
        $caoutchouc->setLibelle('Caoutchouc');
        $caoutchouc->setPriceCatalogue('40.00');
        $caoutchouc->setQuantity(10);
        $caoutchouc->setType('Matière première');
        $caoutchouc->setProvider($this->getReference(ProviderFixtures::PROVIDER_REFERENCE));
        $manager->persist($caoutchouc);

        $vernis = new Piece();
        $vernis->setLibelle('Vernis');
        $vernis->setPriceCatalogue('50.00');
        $vernis->setQuantity(1);
        $vernis->setType("Achetée");
        $vernis->setProvider($this->getReference(ProviderFixtures::PROVIDER_REFERENCE2));
        $manager->persist($vernis);

        $vis = new Piece();
        $vis->setLibelle('Vis');
        $vis->setPriceCatalogue('5.00');
        $vis->setQuantity(20);
        $vis->setType("Achetée");
        $vis->setProvider($this->getReference(ProviderFixtures::PROVIDER_REFERENCE2));
        $manager->persist($vis);

        $filet = new Piece();
        $filet->setLibelle('Filet');
        $filet->setPriceCatalogue('10.00');
        $filet->setQuantity(1);
        $filet->setType("Achetée");
        $filet->setProvider($this->getReference(ProviderFixtures::PROVIDER_REFERENCE2));
        $manager->persist($filet);

        $potpeintureBlanche = new Piece();
        $potpeintureBlanche->setLibelle('Peinture blanche');
        $potpeintureBlanche->setPriceCatalogue('10.00');
        $potpeintureBlanche->setQuantity(1);
        $potpeintureBlanche->setType("Achetée");
        $potpeintureBlanche->setProvider($this->getReference(ProviderFixtures::PROVIDER_REFERENCE2));
        $manager->persist($potpeintureBlanche);

        $potpeintureBleue = new Piece();
        $potpeintureBleue->setLibelle('Peinture bleue');
        $potpeintureBleue->setPriceCatalogue('10.00');
        $potpeintureBleue->setQuantity(1);
        $potpeintureBleue->setType("Achetée");
        $potpeintureBleue->setProvider($this->getReference(ProviderFixtures::PROVIDER_REFERENCE2));
        $manager->persist($potpeintureBleue);

        $chevron = new Piece();
        $chevron->setLibelle('Chevron');
        $chevron->setQuantity(4);
        $chevron->setType("Intermédiaire");
        $chevron->addPiece($bois);
        $chevron->addPiece($caoutchouc);
        $chevron->addPiece($colle);
        $manager->persist($chevron);

        $traverse = new Piece();
        $traverse->setLibelle('Traverse');
        $traverse->setQuantity(2);
        $traverse->setType("Intermédiaire");
        $traverse->addPiece($bois->setQuantity("5"));
        $chevron->addPiece($caoutchouc);
        $traverse->addPiece($colle);
        $manager->persist($traverse);

        $tableSansPeinture = new Piece();
        $tableSansPeinture->setLibelle('Table de ping pong sans peinture');
        $tableSansPeinture->setQuantity(1);
        $tableSansPeinture->setType("Intermédiaire");
        $tableSansPeinture->addPiece($traverse);
        $tableSansPeinture->addPiece($chevron);
        $tableSansPeinture->addPiece($vis);
        $manager->persist($tableSansPeinture);


        $pieceLivrable = new Piece();
        $pieceLivrable->setLibelle('Table de ping pong - couleur bleue, bande blanche');
        $pieceLivrable->setPrice('3200');
        $pieceLivrable->setQuantity(1);
        $pieceLivrable->setType('Livrable');
        $pieceLivrable->setRange($this->getReference(RangeFixtures::RANGE_REFERENCE4));
        $pieceLivrable->addPiece($tableSansPeinture);
        $pieceLivrable->addPiece($potpeintureBleue);
        $pieceLivrable->addPiece($potpeintureBlanche);
        $pieceLivrable->addPiece($vernis);
        $pieceLivrable->addPiece($filet);
        $manager->persist($pieceLivrable);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            RangeFixtures::class,
            ProviderFixtures::class,
        );
    }
}
