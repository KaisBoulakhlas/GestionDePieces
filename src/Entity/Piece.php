<?php

namespace App\Entity;

use App\Repository\PieceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $libelle;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $priceCatalogue;

    /**
     * @ORM\OneToOne(targetEntity=Range::class, inversedBy="piece", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $range;

    /**
     * @ORM\ManyToOne(targetEntity=Piece::class, inversedBy="pieces")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $piece;

    /**
     * @ORM\OneToMany(targetEntity=Piece::class, mappedBy="piece")
     */
    private $pieces;

    /**
     * @ORM\ManyToOne(targetEntity=Provider::class, inversedBy="pieces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $provider;

    /**
     * @ORM\ManyToMany(targetEntity=OrderPurchase::class, mappedBy="pieces")
     */
    private $orderPurchases;


    public function __construct()
    {
        $this->pieces = new ArrayCollection();
        $this->orderPurchases = new ArrayCollection();
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

    public function getPiece(): ?self
    {
        return $this->piece;
    }

    public function setPiece(?self $piece): self
    {
        $this->piece = $piece;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getPieces(): Collection
    {
        return $this->pieces;
    }

    public function addPiece(self $piece): self
    {
        if (!$this->pieces->contains($piece)) {
            $this->pieces[] = $piece;
            $piece->setPiece($this);
        }

        return $this;
    }

    public function removePiece(self $piece): self
    {
        if ($this->pieces->removeElement($piece)) {
            // set the owning side to null (unless already changed)
            if ($piece->getPiece() === $this) {
                $piece->setPiece(null);
            }
        }

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

    /**
     * @return Collection|OrderPurchase[]
     */
    public function getOrderPurchases(): Collection
    {
        return $this->orderPurchases;
    }

    public function addOrderPurchase(OrderPurchase $orderPurchase): self
    {
        if (!$this->orderPurchases->contains($orderPurchase)) {
            $this->orderPurchases[] = $orderPurchase;
            $orderPurchase->addPiece($this);
        }

        return $this;
    }

    public function removeOrderPurchase(OrderPurchase $orderPurchase): self
    {
        if ($this->orderPurchases->removeElement($orderPurchase)) {
            $orderPurchase->removePiece($this);
        }

        return $this;
    }

}
