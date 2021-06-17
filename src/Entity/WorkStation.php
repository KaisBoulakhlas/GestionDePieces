<?php

namespace App\Entity;

use App\Repository\WorkStationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=WorkStationRepository::class)
 */
class WorkStation
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
     * @ORM\OneToMany(targetEntity=Machine::class, mappedBy="workStation")
     */
    private $machines;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="workStations")
     */
    private $userWorkstation;

    /**
     * @ORM\OneToMany(targetEntity=Operation::class, mappedBy="workStation", orphanRemoval=true)
     */
    private $operations;



    public function __construct()
    {
        $this->machines = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    /**
     * @return Collection|Machine[]
     */
    public function getMachines(): Collection
    {
        return $this->machines;
    }

    public function addMachine(Machine $machine): self
    {
        if (!$this->machines->contains($machine)) {
            $this->machines[] = $machine;
            $machine->setWorkStation($this);
        }

        return $this;
    }

    public function removeMachine(Machine $machine): self
    {
        if ($this->machines->removeElement($machine)) {
            // set the owning side to null (unless already changed)
            if ($machine->getWorkStation() === $this) {
                $machine->setWorkStation(null);
            }
        }

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
            $operation->setWorkStation($this);
        }

        return $this;
    }

    public function removeOperation(Operation $operation): self
    {
        if ($this->operations->removeElement($operation)) {
            // set the owning side to null (unless already changed)
            if ($operation->getWorkStation() === $this) {
                $operation->setWorkStation(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->libelle;
    }
}
