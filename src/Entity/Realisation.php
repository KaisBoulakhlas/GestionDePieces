<?php

namespace App\Entity;

use App\Repository\RealisationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Time
     * @var string A "H:i:s" formatted value
     */
    private $time;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="realisations")
     * @ORM\JoinColumn(nullable=false)
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

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
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

}