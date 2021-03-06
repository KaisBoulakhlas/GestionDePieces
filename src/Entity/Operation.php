<?php

namespace App\Entity;

use App\Repository\OperationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OperationRepository::class)
 */
class Operation
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
     * @ORM\Column(type="time")
     * @Assert\NotBlank()
     */
    private $time;

    /**
     * @ORM\ManyToMany(targetEntity=Range::class, inversedBy="operations")
     */
    private $ranges;

    /**
     * @ORM\OneToMany(targetEntity=Realisation::class, mappedBy="operation", cascade={"remove"})
     */
    private $realisations;

    /**
     * @ORM\ManyToOne(targetEntity=Machine::class, inversedBy="operations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $machine;

    /**
     * @ORM\ManyToOne(targetEntity=WorkStation::class, inversedBy="operations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $workStation;


    public function __construct()
    {
        $this->ranges = new ArrayCollection();
        $this->realisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle = null): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getTime(): ?\DateTime
    {
        return $this->time;
    }

    public function setTime(\DateTime $time = null): self
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @return Collection|Range[]
     */
    public function getRanges(): Collection
    {
        return $this->ranges;
    }

    public function addRange(Range $range): self
    {
        if (!$this->ranges->contains($range)) {
            $this->ranges[] = $range;
        }

        return $this;
    }

    public function removeRange(Range $range): self
    {
        $this->ranges->removeElement($range);

        return $this;
    }

    /**
     * @return Collection|Realisation[]
     */
    public function getRealisations(): Collection
    {
        return $this->realisations;
    }

    public function addRealisation(Realisation $realisation): self
    {
        if (!$this->realisations->contains($realisation)) {
            $this->realisations[] = $realisation;
            $realisation->setOperation($this);
        }

        return $this;
    }

    public function removeRealisation(Realisation $realisation): self
    {
        if ($this->realisations->removeElement($realisation)) {
            // set the owning side to null (unless already changed)
            if ($realisation->getOperation() === $this) {
                $realisation->setOperation(null);
            }
        }

        return $this;
    }


    public function getMachine(): ?Machine
    {
        return $this->machine;
    }

    public function setMachine(?Machine $machine): self
    {
        $this->machine = $machine;

        return $this;
    }

    public function getWorkStation(): ?WorkStation
    {
        return $this->workStation;
    }

    public function setWorkStation(?WorkStation $workStation): self
    {
        $this->workStation = $workStation;

        return $this;
    }
}
