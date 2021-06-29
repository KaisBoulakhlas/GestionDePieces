<?php

namespace App\Entity;

use App\Repository\OrderPurchaseLineRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderPurchaseLineRepository::class)
 */
class OrderPurchaseLine
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Piece::class, inversedBy="orderPurchaseLines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $piece;

    /**
     * @ORM\ManyToOne(targetEntity=OrderPurchase::class, inversedBy="orderPurchaseLines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderPurchase;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $priceCatalog;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPiece(): ?Piece
    {
        return $this->piece;
    }

    public function setPiece(?Piece $piece): self
    {
        $this->piece = $piece;

        return $this;
    }

    public function getOrderPurchase(): ?OrderPurchase
    {
        return $this->orderPurchase;
    }

    public function setOrderPurchase(?OrderPurchase $orderPurchase): self
    {
        $this->orderPurchase = $orderPurchase;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPriceCatalog(): ?float
    {
        return $this->priceCatalog;
    }

    public function setPriceCatalog(float $priceCatalog): self
    {
        $this->priceCatalog = $priceCatalog;

        return $this;
    }
}
