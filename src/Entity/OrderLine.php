<?php

namespace App\Entity;

use App\Repository\OrderLineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OrderLineRepository::class)
 */
class OrderLine
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Piece::class, inversedBy="orderLine")
     */
    private $piece;

    /**
     * @ORM\ManyToOne(targetEntity=OrderSale::class, inversedBy="orderLines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderSale;

    /**
     * @ORM\ManyToOne(targetEntity=EstimateLine::class, inversedBy="orderLines")
     */
    private $estimateLine;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
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

    public function getOrderSale(): ?OrderSale
    {
        return $this->orderSale;
    }

    public function setOrderSale(?OrderSale $orderSale): self
    {
        $this->orderSale = $orderSale;

        return $this;
    }

    public function getEstimateLine(): ?EstimateLine
    {
        return $this->estimateLine;
    }

    public function setEstimateLine(?EstimateLine $estimateLine): self
    {
        $this->estimateLine = $estimateLine;

        return $this;
    }





}
