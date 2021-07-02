<?php

namespace App\Entity;

use App\Repository\PieceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PieceRepository::class)
 */
class Piece
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Libellé vide.")
     */
    private $libelle;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Quantité vide.")
     */
    private $quantity;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     *
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Type vide.")
     */
    private $type;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $priceCatalogue;

    /**
     * @ORM\OneToOne(targetEntity=Range::class, inversedBy="piece", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $range;

    /**
     * @ORM\ManyToOne(targetEntity=Provider::class, inversedBy="pieces")
     * @ORM\JoinColumn(nullable=true)
     */
    private $provider;

    /**
     * @ORM\OneToMany(targetEntity=OrderLine::class, mappedBy="piece")
     */
    private $orderLine;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Référence à remplir.")
     */
    private $reference;

    /**
     * @ORM\OneToMany(targetEntity=PieceUsed::class, mappedBy="parent", cascade={"persist"}, orphanRemoval=true)
     */
    private $pieceUseds;

    /**
     * @ORM\OneToMany(targetEntity=OrderPurchaseLine::class, mappedBy="piece", orphanRemoval=true)
     */
    private $orderPurchaseLines;

    /**
     * @ORM\OneToMany(targetEntity=EstimateLine::class, mappedBy="piece", orphanRemoval=true)
     */
    private $estimateLines;

    public function __construct()
    {
        $this->pieceUseds = new ArrayCollection();
        $this->orderPurchaseLines = new ArrayCollection();
        $this->estimateLines = new ArrayCollection();
        $this->orderLine = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPriceCatalogue(): ?string
    {
        return $this->priceCatalogue;
    }

    public function setPriceCatalogue(string $priceCatalogue): self
    {
        $this->priceCatalogue = $priceCatalogue;

        return $this;
    }


    public function getRange(): ?Range
    {
        return $this->range;
    }

    public function setRange(Range $range): self
    {
        $this->range = $range;

        return $this;
    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getOrderLine(): ?OrderLine
    {
        return $this->orderLine;
    }

    public function setOrderLine(?OrderLine $orderLine): self
    {
        $this->orderLine = $orderLine;

        return $this;
    }

    public function __toString()
    {
        return $this->libelle;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return Collection|PieceUsed[]
     */
    public function getPieceUseds(): Collection
    {
        return $this->pieceUseds;
    }

    public function addPieceUsed(PieceUsed $pieceUsed): self
    {
        if (!$this->pieceUseds->contains($pieceUsed)) {
            $this->pieceUseds[] = $pieceUsed;
            $pieceUsed->setParent($this);
        }

        return $this;
    }

    public function removePieceUsed(PieceUsed $pieceUsed): self
    {
        if ($this->pieceUseds->removeElement($pieceUsed)) {
            // set the owning side to null (unless already changed)
            if ($pieceUsed->getParent() === $this) {
                $pieceUsed->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OrderPurchaseLine[]
     */
    public function getOrderPurchaseLines(): Collection
    {
        return $this->orderPurchaseLines;
    }

    public function addOrderPurchaseLine(OrderPurchaseLine $orderPurchaseLine): self
    {
        if (!$this->orderPurchaseLines->contains($orderPurchaseLine)) {
            $this->orderPurchaseLines[] = $orderPurchaseLine;
            $orderPurchaseLine->setPiece($this);
        }

        return $this;
    }

    public function removeOrderPurchaseLine(OrderPurchaseLine $orderPurchaseLine): self
    {
        if ($this->orderPurchaseLines->removeElement($orderPurchaseLine)) {
            // set the owning side to null (unless already changed)
            if ($orderPurchaseLine->getPiece() === $this) {
                $orderPurchaseLine->setPiece(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EstimateLine[]
     */
    public function getEstimateLines(): Collection
    {
        return $this->estimateLines;
    }

    public function addEstimateLine(EstimateLine $estimateLine): self
    {
        if (!$this->estimateLines->contains($estimateLine)) {
            $this->estimateLines[] = $estimateLine;
            $estimateLine->setPiece($this);
        }

        return $this;
    }

    public function removeEstimateLine(EstimateLine $estimateLine): self
    {
        if ($this->estimateLines->removeElement($estimateLine)) {
            // set the owning side to null (unless already changed)
            if ($estimateLine->getPiece() === $this) {
                $estimateLine->setPiece(null);
            }
        }

        return $this;
    }

    public function addOrderLine(OrderLine $orderLine): self
    {
        if (!$this->orderLine->contains($orderLine)) {
            $this->orderLine[] = $orderLine;
            $orderLine->setPiece($this);
        }

        return $this;
    }

    public function removeOrderLine(OrderLine $orderLine): self
    {
        if ($this->orderLine->removeElement($orderLine)) {
            // set the owning side to null (unless already changed)
            if ($orderLine->getPiece() === $this) {
                $orderLine->setPiece(null);
            }
        }

        return $this;
    }

}
