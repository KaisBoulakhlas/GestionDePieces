<?php


namespace App\Service;


use App\Entity\Estimate;
use App\Entity\OrderPurchase;
use App\Entity\OrderSale;


class AddingQuantities
{
    /**
     * @param Object $entity
     * @return array
     */
    public function AddQuantityWhenTwoPieceIdentics(Object $entity){
        $res = [];
        if (is_object($entity)) {
            if($entity instanceof OrderPurchase){
                foreach ($entity->getOrderPurchaseLines() as $orderPurchaseLine) {
                    if (isset($res[$orderPurchaseLine->getPiece()->getId()])) {
                        $entity->removeOrderPurchaseLine($orderPurchaseLine);
                        $pieceUsed = $res[$orderPurchaseLine->getPiece()->getId()];
                        $pieceUsed->setQuantity($pieceUsed->getQuantity() + $orderPurchaseLine->getQuantity());
                        $entity->addOrderPurchaseLine($pieceUsed);
                        continue;
                    }
                    $res[$orderPurchaseLine->getPiece()->getId()] = $orderPurchaseLine;
                }
            }

            if($entity instanceof Estimate){
                foreach ($entity->getEstimateLines() as $estimateLine) {
                    if (isset($res[$estimateLine->getPiece()->getId()])) {
                        $entity->removeEstimateLine($estimateLine);
                        $pieceUsed = $res[$estimateLine->getPiece()->getId()];
                        $pieceUsed->setQuantity($pieceUsed->getQuantity() + $estimateLine->getQuantity());
                        $entity->addEstimateLine($pieceUsed);
                        continue;
                    }
                    $res[$estimateLine->getPiece()->getId()] = $estimateLine;
                }
            }
        }

        return $res;
    }

    public function AddQuantityWhenTwoEstimateLinesIdentics(OrderSale $orderSale){
        $res = [];
        foreach ($orderSale->getOrderLines() as $orderLine) {
            if (isset($res[$orderLine->getEstimateLine()->getId()]) &&
                $res[$orderLine->getEstimateLine()->getId()]->getEstimateLine()->getPrice() === $orderLine->getEstimateLine()->getPrice()) {
                $orderSale->removeOrderLine($orderLine);
                $orderLinePieceUsed = $res[$orderLine->getEstimateLine()->getId()];
                $orderLinePieceUsed->setQuantity($orderLinePieceUsed->getQuantity() + $orderLine->getEstimateLine()->getQuantity());
                $orderSale->addOrderLine($orderLinePieceUsed);
                continue;
            }
            $res[$orderLine->getEstimateLine()->getId()] = $orderLine
                ->setQuantity($orderLine->getEstimateLine()->getQuantity())
                ->setPrice($orderLine->getEstimateLine()->getPrice())
                ->setPiece($orderLine->getEstimateLine()->getPiece());
        }
        return $res;
    }
}