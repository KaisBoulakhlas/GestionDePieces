<?php

namespace App\DataFixtures;

use App\Entity\OrderPurchase;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderPurchaseFixtures extends Fixture implements DependentFixtureInterface
{
    public const ORDER_PURCHASE = "order_purchase";

    public function load(ObjectManager $manager)
    {
       $orderPurchase = new OrderPurchase();
       $orderPurchase->setLibelle("Pot de peinture blanche et bleue");
       $orderPurchase->setProvider($this->getReference(ProviderFixtures::PROVIDER_REFERENCE));
       $orderPurchase->setDateDeliveryPredicted(new \DateTime('02/07/2021 12:50:00'));
       $this->setReference(self::ORDER_PURCHASE,$orderPurchase);
       $manager->persist($orderPurchase);

       $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            ProviderFixtures::class,
        );
    }

}
