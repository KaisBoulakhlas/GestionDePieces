<?php

namespace App\Entity;

use App\Repository\OrderPurchaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OrderPurchaseRepository::class)
 */
class OrderPurchase
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
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $dateDeliveryPredicted;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $dateDeliveryReal;

    /**
     * @ORM\ManyToMany(targetEntity=Piece::class, inversedBy="orderPurchases")
     */
    private $pieces;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $priceCatalogue;

    public function __construct()
    {
        $this->pieces = new ArrayCollection();
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

    public function getDateDeliveryPredicted(): ?\DateTimeInterface
    {
        return $this->dateDeliveryPredicted;
    }

    public function setDateDeliveryPredicted(\DateTimeInterface $dateDeliveryPredicted): self
    {
        $this->dateDeliveryPredicted = $dateDeliveryPredicted;

        return $this;
    }

    public function getDateDeliveryReal(): ?\DateTimeInterface
    {
        return $this->dateDeliveryReal;
    }

    public function setDateDeliveryReal(\DateTimeInterface $dateDeliveryReal): self
    {
        $this->dateDeliveryReal = $dateDeliveryReal;

        return $this;
    }

    /**
     * @return Collection|Piece[]
     */
    public function getPieces(): Collection
    {
        return $this->pieces;
    }

    public function addPiece(Piece $piece): self
    {
        if (!$this->pieces->contains($piece)) {
            $this->pieces[] = $piece;
        }

        return $this;
    }

    public function removePiece(Piece $piece): self
    {
        $this->pieces->removeElement($piece);

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
}
