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

    public function setPrice(?float $price): self
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
}
