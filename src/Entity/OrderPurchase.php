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
     */
    private $dateDeliveryPredicted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDeliveryReal;

    /**
     * @ORM\OneToMany(targetEntity=OrderPurchaseLine::class, mappedBy="orderPurchase", cascade={"persist"}, orphanRemoval=true)
     */
    private $orderPurchaseLines;

    /**
     * @ORM\ManyToOne(targetEntity=Provider::class, inversedBy="orderPurchases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $provider;

    public function __construct()
    {
        $this->orderPurchaseLines = new ArrayCollection();
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

    public function setDateDeliveryReal(\DateTimeInterface $dateDeliveryReal = null): self
    {
        $this->dateDeliveryReal = $dateDeliveryReal;

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
            $orderPurchaseLine->setOrderPurchase($this);
        }

        return $this;
    }

    public function removeOrderPurchaseLine(OrderPurchaseLine $orderPurchaseLine): self
    {
        if ($this->orderPurchaseLines->removeElement($orderPurchaseLine)) {
            // set the owning side to null (unless already changed)
            if ($orderPurchaseLine->getOrderPurchase() === $this) {
                $orderPurchaseLine->setOrderPurchase(null);
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

    public function __toString() : string
    {
        return $this->libelle;
    }

}
