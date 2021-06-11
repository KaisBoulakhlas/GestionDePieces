<?php

namespace App\Entity;

use App\Repository\RangeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RangeRepository::class)
 */
class Range
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ranges")
     * @ORM\JoinColumn(nullable=true)
     */
    private $userWorkstation;

    /**
     * @ORM\ManyToMany(targetEntity=Operation::class, mappedBy="ranges")
     */
    private $operations;

    /**
     * @ORM\OneToOne(targetEntity=Piece::class, mappedBy="range", cascade={"persist", "remove"})
     */
    private $piece;

    public function __construct()
    {
        $this->operations = new ArrayCollection();
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

    public function getUserWorkstation(): ?User
    {
        return $this->userWorkstation;
    }

    public function setUserWorkstation(?User $userWorkstation): self
    {
        $this->userWorkstation = $userWorkstation;

        return $this;
    }

    /**
     * @return Collection|Operation[]
     */
    public function getOperations(): Collection
    {
        return $this->operations;
    }

    public function addOperation(Operation $operation): self
    {
        if (!$this->operations->contains($operation)) {
            $this->operations[] = $operation;
            $operation->addRange($this);
        }

        return $this;
    }

    public function removeOperation(Operation $operation): self
    {
        if ($this->operations->removeElement($operation)) {
            $operation->removeRange($this);
        }

        return $this;
    }

    public function getPiece(): ?Piece
    {
        return $this->piece;
    }

    public function setPiece(Piece $piece): self
    {
        // set the owning side of the relation if necessary
        if ($piece->getRange() !== $this) {
            $piece->setRange($this);
        }

        $this->piece = $piece;

        return $this;
    }
}
