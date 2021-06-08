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
     * @Assert\NotBlank()
     */
    private $libelle;

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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     * @Assert\NotBlank()
     */
    private $priceCatalogue;

    /**
     * @ORM\OneToOne(targetEntity=Range::class, inversedBy="piece", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $range;

    /**
     * @ORM\ManyToOne(targetEntity=Provider::class, inversedBy="pieces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $provider;

    /**
     * @ORM\ManyToMany(targetEntity=OrderPurchase::class, mappedBy="pieces")
     */
    private $orderPurchases;

    /**
     * @ORM\ManyToMany(targetEntity=Piece::class, inversedBy="piecesChildren")
     */
    private $piecesParentes;

    /**
     * @ORM\ManyToMany(targetEntity=Piece::class, mappedBy="piecesParentes")
     */
    private $piecesChildren;

    /**
     * @ORM\ManyToOne(targetEntity=OrderLine::class, inversedBy="piece")
     */
    private $orderLine;

    /**
     * @ORM\ManyToOne(targetEntity=EstimateLine::class, inversedBy="piece")
     */
    private $estimateLine;

    public function __construct()
    {
        $this->orderPurchases = new ArrayCollection();
        $this->piecesParentes = new ArrayCollection();
        $this->piecesChildren = new ArrayCollection();
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

    /**
     * @return Collection|self[]
     */
    public function getPiecesParentes(): Collection
    {
        return $this->piecesParentes;
    }

    public function addPiecesParente(self $piecesParente): self
    {
        if (!$this->piecesParentes->contains($piecesParente)) {
            $this->piecesParentes[] = $piecesParente;
        }

        return $this;
    }

    public function removePiecesParente(self $piecesParente): self
    {
        $this->piecesParentes->removeElement($piecesParente);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getPiecesChildren(): Collection
    {
        return $this->piecesChildren;
    }

    public function addPiece(self $pieceChildren): self
    {
        if (!$this->piecesChildren->contains($pieceChildren)) {
            $this->piecesChildren[] = $pieceChildren;
            $pieceChildren->addPiecesParente($this);
        }

        return $this;
    }

    public function removePiece(self $pieceChildren): self
    {
        if ($this->piecesChildren->removeElement($pieceChildren)) {
            $pieceChildren->removePiecesParente($this);
        }

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
