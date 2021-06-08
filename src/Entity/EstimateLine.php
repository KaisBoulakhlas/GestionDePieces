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
     * @ORM\ManyToOne(targetEntity=Estimate::class, inversedBy="estimateLines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $estimate;

    /**
     * @ORM\OneToMany(targetEntity=Piece::class, mappedBy="estimateLine")
     */
    private $piece;

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

    public function getEstimate(): ?Estimate
    {
        return $this->estimate;
    }

    public function setEstimate(?Estimate $estimate): self
    {
        $this->estimate = $estimate;

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
            $piece->setEstimateLine($this);
        }

        return $this;
    }

    public function removePiece(Piece $piece): self
    {
        if ($this->piece->removeElement($piece)) {
            // set the owning side to null (unless already changed)
            if ($piece->getEstimateLine() === $this) {
                $piece->setEstimateLine(null);
            }
        }

        return $this;
    }
}
