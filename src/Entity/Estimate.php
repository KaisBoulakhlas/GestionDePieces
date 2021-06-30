<?php

namespace App\Entity;

use App\Repository\EstimateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EstimateRepository::class)
 */
class Estimate
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
    private $title;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $deadline;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="estimates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\OneToMany(targetEntity=EstimateLine::class, mappedBy="estimate", cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $estimateLines;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    public function __construct()
    {
        $this->estimateLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(\DateTimeInterface $deadline): self
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection|EstimateLine[]
     */
    public function getEstimateLines(): Collection
    {
        return $this->estimateLines;
    }

    public function addEstimateLine(EstimateLine $estimateLine): self
    {
        if (!$this->estimateLines->contains($estimateLine)) {
            $this->estimateLines[] = $estimateLine;
            $estimateLine->setEstimate($this);
        }

        return $this;
    }

    public function removeEstimateLine(EstimateLine $estimateLine): self
    {
        if ($this->estimateLines->removeElement($estimateLine)) {
            // set the owning side to null (unless already changed)
            if ($estimateLine->getEstimate() === $this) {
                $estimateLine->setEstimate(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }
}
