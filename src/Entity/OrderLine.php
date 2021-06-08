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
     * @ORM\Column(type="decimal", precision=5, scale=2)
     * @Assert\NotBlank()
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity=Piece::class, mappedBy="orderLine")
     */
    private $piece;

    /**
     * @ORM\ManyToOne(targetEntity=OrderSale::class, inversedBy="orderLines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderSale;


    public function __construct()
    {
        $this->piece = new ArrayCollection();
    }

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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|Piece[]
     */
    public function getPiece(): Collection
    {
        return $this->piece;
    }

    public function addPiece(Piece $piece): self
    {
        if (!$this->piece->contains($piece)) {
            $this->piece[] = $piece;
            $piece->setOrderLine($this);
        }

        return $this;
    }

    public function removePiece(Piece $piece): self
    {
        if ($this->piece->removeElement($piece)) {
            // set the owning side to null (unless already changed)
            if ($piece->getOrderLine() === $this) {
                $piece->setOrderLine(null);
            }
        }

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
}
