<?php

namespace App\DataFixtures;

use App\Entity\OrderPurchaseLine;
use App\Entity\Piece;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderPurchaseLineFixtures extends Fixture implements DependentFixtureInterface
{
    public const ORDER_PURCHASE_LINE_1 = "order_purchase_line_peinture_bleue";
    public const ORDER_PURCHASE_LINE_2 = "order_purchase_line_peinture_blanche";

    public function load(ObjectManager $manager)
    {
        $orderPurchaseLine1 = new OrderPurchaseLine();
        $orderPurchaseLine1->setQuantity(10);
        $orderPurchaseLine1->setPriceCatalog(10.50);
        $orderPurchaseLine1->setPiece($this->getReference(PiecesFixtures::PIECE_REFERENCE5));
        $orderPurchaseLine1->setOrderPurchase($this->getReference(OrderPurchaseFixtures::ORDER_PURCHASE));
        $this->setReference(self::ORDER_PURCHASE_LINE_1, $orderPurchaseLine1);
        $manager->persist($orderPurchaseLine1);

        $orderPurchaseLine2 = new OrderPurchaseLine();
        $orderPurchaseLine2->setQuantity(5);
        $orderPurchaseLine2->setPriceCatalog(8.50);
        $orderPurchaseLine2->setPiece($this->getReference(PiecesFixtures::PIECE_REFERENCE6));
        $orderPurchaseLine2->setOrderPurchase($this->getReference(OrderPurchaseFixtures::ORDER_PURCHASE));
        $this->setReference(self::ORDER_PURCHASE_LINE_2, $orderPurchaseLine2);
        $manager->persist($orderPurchaseLine2);

        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            PiecesFixtures::class,
            OrderPurchaseFixtures::class,
        );
    }
}
