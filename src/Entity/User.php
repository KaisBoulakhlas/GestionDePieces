<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(
     *     message = "L'email '{{ value }}' n'est pas valide."
     * )
     */
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    private $plainPassword = null;

    /**
     * @ORM\OneToMany(targetEntity=Range::class, mappedBy="userWorkstation")
     */
    private $ranges;

    /**
     * @ORM\OneToMany(targetEntity=Realisation::class, mappedBy="userWorkStation")
     */
    private $realisations;

    /**
     * @ORM\OneToMany(targetEntity=WorkStation::class, mappedBy="userWorkstation")
     */
    private $workStations;


    public function __construct()
    {
        $this->ranges = new ArrayCollection();
        $this->realisations = new ArrayCollection();
        $this->workStations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_COMMERCIAL' . 'ROLE_OUVRIER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * This method can be removed in Symfony 6.0 - is not needed for apps that do not check user passwords.
     *
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * This method can be removed in Symfony 6.0 - is not needed for apps that do not check user passwords.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername() : string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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
            $range->setUserWorkstation($this);
        }

        return $this;
    }

    public function removeRange(Range $range): self
    {
        if ($this->ranges->removeElement($range)) {
            // set the owning side to null (unless already changed)
            if ($range->getUserWorkstation() === $this) {
                $range->setUserWorkstation(null);
            }
        }

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
            $realisation->setUserWorkStation($this);
        }

        return $this;
    }

    public function removeRealisation(Realisation $realisation): self
    {
        if ($this->realisations->removeElement($realisation)) {
            // set the owning side to null (unless already changed)
            if ($realisation->getUserWorkStation() === $this) {
                $realisation->setUserWorkStation(null);
            }
        }

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return Collection|WorkStation[]
     */
    public function getWorkStations(): Collection
    {
        return $this->workStations;
    }

    public function addWorkStation(WorkStation $workStation): self
    {
        if (!$this->workStations->contains($workStation)) {
            $this->workStations[] = $workStation;
            $workStation->setUserWorkstation($this);
        }

        return $this;
    }

    public function removeWorkStation(WorkStation $workStation): self
    {
        if ($this->workStations->removeElement($workStation)) {
            // set the owning side to null (unless already changed)
            if ($workStation->getUserWorkstation() === $this) {
                $workStation->setUserWorkstation(null);
            }
        }

        return $this;
    }

}
