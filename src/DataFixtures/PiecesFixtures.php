<?php
namespace App\DataFixtures;

use Faker;
use App\Entity\Piece;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PiecesFixtures extends Fixture implements DependentFixtureInterface
{
    public const PIECE_REFERENCE1 = "piece_reference_table_livrable";
    public const PIECE_REFERENCE2 = "piece_reference_table_sans_peinture";
    public const PIECE_REFERENCE3 = "piece_reference_chevron";
    public const PIECE_REFERENCE4 = "piece_reference_traverse";


    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        $bois = new Piece();
        $bois->setLibelle('Bois');
        $bois->setPriceCatalogue('50.00');
        $bois->setType('Matière première');
        $bois->setProvider($this->getReference(ProviderFixtures::PROVIDER_REFERENCE));
        $bois->setReference($faker->unique()->regexify('[A-Z]{5}[0-4]{3}'));
        $bois->setQuantity(650);
        $manager->persist($bois);

        $colle = new Piece();
        $colle->setLibelle('Colle');
        $colle->setPriceCatalogue('50.00');
        $colle->setQuantity(320);
        $colle->setType('Matière première');
        $colle->setProvider($this->getReference(ProviderFixtures::PROVIDER_REFERENCE));
        $colle->setReference($faker->unique()->regexify('[A-Z]{5}[0-4]{3}'));
        $manager->persist($colle);

        $caoutchouc = new Piece();
        $caoutchouc->setLibelle('Caoutchouc');
        $caoutchouc->setPriceCatalogue('40.00');
        $caoutchouc->setQuantity(100);
        $caoutchouc->setType('Matière première');
        $caoutchouc->setProvider($this->getReference(ProviderFixtures::PROVIDER_REFERENCE));
        $caoutchouc->setReference($faker->unique()->regexify('[A-Z]{5}[0-4]{3}'));
        $manager->persist($caoutchouc);

        $vernis = new Piece();
        $vernis->setLibelle('Vernis');
        $vernis->setPriceCatalogue('50.00');
        $vernis->setQuantity(65);
        $vernis->setType("Achetée");
        $vernis->setProvider($this->getReference(ProviderFixtures::PROVIDER_REFERENCE2));
        $vernis->setReference($faker->unique()->regexify('[A-Z]{5}[0-4]{3}'));
        $manager->persist($vernis);

        $vis = new Piece();
        $vis->setLibelle('Vis');
        $vis->setPriceCatalogue('5.00');
        $vis->setQuantity(500);
        $vis->setType("Achetée");
        $vis->setProvider($this->getReference(ProviderFixtures::PROVIDER_REFERENCE2));
        $vis->setReference($faker->unique()->regexify('[A-Z]{5}[0-4]{3}'));
        $manager->persist($vis);

        $filet = new Piece();
        $filet->setLibelle('Filet');
        $filet->setPriceCatalogue('10.00');
        $filet->setQuantity(150);
        $filet->setType("Achetée");
        $filet->setProvider($this->getReference(ProviderFixtures::PROVIDER_REFERENCE2));
        $filet->setReference($faker->unique()->regexify('[A-Z]{5}[0-4]{3}'));
        $manager->persist($filet);

        $potpeintureBlanche = new Piece();
        $potpeintureBlanche->setLibelle('Peinture blanche');
        $potpeintureBlanche->setPriceCatalogue('10.00');
        $potpeintureBlanche->setQuantity(40);
        $potpeintureBlanche->setType("Achetée");
        $potpeintureBlanche->setProvider($this->getReference(ProviderFixtures::PROVIDER_REFERENCE2));
        $potpeintureBlanche->setReference($faker->unique()->regexify('[A-Z]{5}[0-4]{3}'));
        $manager->persist($potpeintureBlanche);

        $potpeintureBleue = new Piece();
        $potpeintureBleue->setLibelle('Peinture bleue');
        $potpeintureBleue->setPriceCatalogue('10.00');
        $potpeintureBleue->setQuantity(30);
        $potpeintureBleue->setType("Achetée");
        $potpeintureBleue->setProvider($this->getReference(ProviderFixtures::PROVIDER_REFERENCE2));
        $potpeintureBleue->setReference($faker->unique()->regexify('[A-Z]{5}[0-4]{3}'));
        $manager->persist($potpeintureBleue);

        $chevron = new Piece();
        $chevron->setLibelle('Chevron');
        $chevron->setQuantity(4);
        $chevron->setType("Intermédiaire");
        $chevron->setReference($faker->unique()->regexify('[A-Z]{5}[0-4]{3}'));
        $this->setReference(self::PIECE_REFERENCE3,$chevron);
        $manager->persist($chevron);

        $traverse = new Piece();
        $traverse->setLibelle('Traverse');
        $traverse->setQuantity(2);
        $traverse->setType("Intermédiaire");
        $traverse->setReference($faker->unique()->regexify('[A-Z]{5}[0-4]{3}'));
        $this->setReference(self::PIECE_REFERENCE4,$traverse);
        $manager->persist($traverse);

        $tableSansPeinture = new Piece();
        $tableSansPeinture->setLibelle('Table de ping pong sans peinture');
        $tableSansPeinture->setQuantity(5);
        $tableSansPeinture->setType("Intermédiaire");
        $tableSansPeinture->setReference($faker->unique()->regexify('[A-Z]{5}[0-4]{3}'));
        $this->setReference(self::PIECE_REFERENCE2,$tableSansPeinture);
        $manager->persist($tableSansPeinture);


        $pieceLivrable = new Piece();
        $pieceLivrable->setLibelle('Table de ping pong - couleur bleue, bande blanche');
        $pieceLivrable->setPrice('3200');
        $pieceLivrable->setQuantity(8);
        $pieceLivrable->setType('Livrable');
        $pieceLivrable->setReference($faker->unique()->regexify('[A-Z]{5}[0-4]{3}'));
        $this->setReference(self::PIECE_REFERENCE1,$pieceLivrable);
        $manager->persist($pieceLivrable);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ProviderFixtures::class,
        );
    }
}
