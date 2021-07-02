<?php

namespace App\DataFixtures;

use App\Entity\OrderSale;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderSaleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $orderSale = new OrderSale();
        $orderSale->setLibelle("Commande de vente n°1");
        $orderSale->setDate(new \DateTime("12/07/2020"));
        $orderSale->setDescription("kjdklfgglkg");
        $orderSale->setCustomer($this->getReference('customer_1'));
        $orderSale->setStatus(0);
        $manager->persist($orderSale);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CustomerFixtures::class,
        );
    }
}
