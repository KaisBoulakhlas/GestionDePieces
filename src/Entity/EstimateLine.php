<?php

namespace App\Entity;

use App\Repository\EstimateLineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EstimateLineRepository::class)
 */
class EstimateLine
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private ?int $quantity;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    private ?float $price;

    /**
     * @ORM\ManyToOne(targetEntity=Estimate::class, inversedBy="estimateLines")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Estimate $estimate;

    /**
     * @ORM\ManyToOne(targetEntity=Piece::class, inversedBy="estimateLines")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Piece $piece;

    /**
     * @ORM\OneToMany(targetEntity=OrderLine::class, mappedBy="estimateLine", cascade={"persist"})
     */
    private $orderLines;

    public function __construct()
    {
        $this->orderLines = new ArrayCollection();
    }


    public function __toString()
    {
        return $this->getEstimate()->getTitle() . " - " . $this->getPiece() . " - ". $this->quantity . " - " . $this->price . "€";
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getEstimate(): ?Estimate
    {
        return $this->estimate;
    }

    public function setEstimate(?Estimate $estimate): self
    {
        $this->estimate = $estimate;

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

    /**
     * @return Collection|OrderLine[]
     */
    public function getOrderLines(): Collection
    {
        return $this->orderLines;
    }

    public function addOrderLine(OrderLine $orderLine): self
    {
        if (!$this->orderLines->contains($orderLine)) {
            $this->orderLines[] = $orderLine;
            $orderLine->setEstimateLine($this);
        }

        return $this;
    }

    public function removeOrderLine(OrderLine $orderLine): self
    {
        if ($this->orderLines->removeElement($orderLine)) {
            // set the owning side to null (unless already changed)
            if ($orderLine->getEstimateLine() === $this) {
                $orderLine->setEstimateLine(null);
            }
        }

        return $this;
    }


}
