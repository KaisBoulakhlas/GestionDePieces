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
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="workStation")
     */
    private $userWorkstation;

    /**
     * @ORM\OneToMany(targetEntity=Machine::class, mappedBy="workStation")
     */
    private $machines;

    public function __construct()
    {
        $this->userWorkstation = new ArrayCollection();
        $this->machines = new ArrayCollection();
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
     * @return Collection|user[]
     */
    public function getUserworkstation(): Collection
    {
        return $this->userWorkstation;
    }

    public function addUserworkstation(User $userWorkstation): self
    {
        if (!$this->userWorkstation->contains($userWorkstation)) {
            $this->userWorkstation[] = $userWorkstation;
            $userWorkstation->setWorkStation($this);
        }

        return $this;
    }

    public function removeUserworkstation(User $userWorkstation): self
    {
        if ($this->userWorkstation->removeElement($userWorkstation)) {
            // set the owning side to null (unless already changed)
            if ($userWorkstation->getWorkStation() === $this) {
                $userWorkstation->setWorkStation(null);
            }
        }

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

}
