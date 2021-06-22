<?php

namespace App\Entity;

use App\Repository\RangeRealisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RangeRealisationRepository::class)
 */
class RangeRealisation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Range::class, inversedBy="rangeRealisations")
     */
    private $range;

    /**
     * @ORM\OneToMany(targetEntity=Realisation::class, mappedBy="rangeRealisation", cascade={"persist","remove"})
     */
    private $realisations;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="rangeRealisations")
     */
    private $userWorkStation;

    public function __construct()
    {
        $datetimezone = new \DateTimeZone('Europe/Paris');
        $this->date = new \DateTime();
        $this->date->setTimezone($datetimezone);
        $this->realisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getRange(): ?Range
    {
        return $this->range;
    }

    public function setRange(?Range $range): self
    {
        $this->range = $range;

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
            $realisation->setRangeRealisation($this);
        }

        return $this;
    }

    public function removeRealisation(Realisation $realisation): self
    {
        if ($this->realisations->removeElement($realisation)) {
            // set the owning side to null (unless already changed)
            if ($realisation->getRangeRealisation() === $this) {
                $realisation->setRangeRealisation(null);
            }
        }

        return $this;
    }

    public function getUserWorkStation(): ?User
    {
        return $this->userWorkStation;
    }

    public function setUserWorkStation(?User $userWorkStation): self
    {
        $this->userWorkStation = $userWorkStation;

        return $this;
    }
}
