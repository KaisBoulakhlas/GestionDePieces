<?php

namespace App\Entity;

use App\Repository\RealisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Time;

/**
 * @ORM\Entity(repositoryClass=RealisationRepository::class)
 */
class Realisation
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="realisations")
     * @ORM\JoinColumn(nullable=true)
     */
    private $userWorkStation;

    /**
     * @ORM\ManyToOne(targetEntity=Operation::class, inversedBy="realisations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $operation;

    /**
     * @ORM\ManyToOne(targetEntity=Machine::class, inversedBy="realisations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $machine;

    /**
     * @ORM\ManyToOne(targetEntity=RangeRealisation::class, inversedBy="realisations")
     */
    private $rangeRealisation;

    /**
     * @ORM\ManyToOne(targetEntity=Workstation::class, inversedBy="realisations")
     */
    private $workstation;


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

    public function getTime(): \DateTime
    {
        return $this->time;
    }

    public function setTime(\DateTime $time = null): self
    {
        $this->time = $time;

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

    public function getOperation(): ?Operation
    {
        return $this->operation;
    }

    public function setOperation(?Operation $operation): self
    {
        $this->operation = $operation;

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

    public function getRangeRealisation(): ?RangeRealisation
    {
        return $this->rangeRealisation;
    }

    public function setRangeRealisation(?RangeRealisation $rangeRealisation): self
    {
        $this->rangeRealisation = $rangeRealisation;

        return $this;
    }

    public function getWorkstation(): ?Workstation
    {
        return $this->workstation;
    }

    public function setWorkstation(?Workstation $workstation): self
    {
        $this->workstation = $workstation;

        return $this;
    }


}
