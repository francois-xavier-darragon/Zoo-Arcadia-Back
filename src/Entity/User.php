<?php

namespace App\Entity;

use App\Entity\Trait\SoftDeletableTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableTrait;
    use SoftDeletableTrait;

    CONST ROLES = [
        'ROLE_ADMIN'=> 'Administrateur',
        'ROLE_VETERINARY' => 'Vétérinaire',
        'ROLE_WORKER' => 'employé',
    ];
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Image $avatar = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?int $phone = null;

    #[ORM\Column(nullable: true)]
    private ?array $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $apiToken = null;

    /**
     * @var Collection<int, Notice>
     */
    #[ORM\OneToMany(targetEntity: Notice::class, mappedBy: 'user')]
    private Collection $notices;

    /**
     * @var Collection<int, VeterinaryReport>
     */
    #[ORM\OneToMany(targetEntity: VeterinaryReport::class, mappedBy: 'user')]
    private Collection $veterinaryReports;

    /**
     * @var Collection<int, Food>
     */
    #[ORM\OneToMany(targetEntity: Food::class, mappedBy: 'prescribedBy')]
    private Collection $prescribedFoods;

    /**
     * @var Collection<int, FoodAdministration>
     */
    #[ORM\OneToMany(targetEntity: FoodAdministration::class, mappedBy: 'administeredBy')]
    private Collection $foodAdministrations;

    public function __construct()
    {
        $this->notices = new ArrayCollection();
        $this->veterinaryReports = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->setDeletedAt(null);
        $this->prescribedFoods = new ArrayCollection();
        $this->foodAdministrations = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->lastName . $this->firstName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }


    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection<int, Notice>
     */
    public function getNotices(): Collection
    {
        return $this->notices;
    }

    public function addNotice(Notice $notice): static
    {
        if (!$this->notices->contains($notice)) {
            $this->notices->add($notice);
            $notice->setUser($this);
        }

        return $this;
    }

    public function removeNotice(Notice $notice): static
    {
        if ($this->notices->removeElement($notice)) {
            // set the owning side to null (unless already changed)
            if ($notice->getUser() === $this) {
                $notice->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, VeterinaryReport>
     */
    public function getVeterinaryReports(): Collection
    {
        return $this->veterinaryReports;
    }

    public function addVeterinaryReport(VeterinaryReport $veterinaryReport): static
    {
        if (!$this->veterinaryReports->contains($veterinaryReport)) {
            $this->veterinaryReports->add($veterinaryReport);
            $veterinaryReport->setUser($this);
        }

        return $this;
    }

    public function removeVeterinaryReport(VeterinaryReport $veterinaryReport): static
    {
        if ($this->veterinaryReports->removeElement($veterinaryReport)) {
            // set the owning side to null (unless already changed)
            if ($veterinaryReport->getUser() === $this) {
                $veterinaryReport->setUser(null);
            }
        }

        return $this;
    }

    public function getAvatar(): ?Image
    {
        return $this->avatar;
    }

    public function setAvatar(?Image $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(?int $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?array
    {
        return $this->address;
    }

    public function setAddress(?array $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(?string $apiToken): static
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    /**
     * @return Collection<int, Food>
     */
    public function getPrescribedFoods(): Collection
    {
        return $this->prescribedFoods;
    }

    public function addPrescribedFood(Food $prescribedFood): static
    {
        if (!$this->prescribedFoods->contains($prescribedFood)) {
            $this->prescribedFoods->add($prescribedFood);
            $prescribedFood->setPrescribedBy($this);
        }

        return $this;
    }

    public function removePrescribedFood(Food $prescribedFood): static
    {
        if ($this->prescribedFoods->removeElement($prescribedFood)) {
            // set the owning side to null (unless already changed)
            if ($prescribedFood->getPrescribedBy() === $this) {
                $prescribedFood->setPrescribedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FoodAdministration>
     */
    public function getFoodAdministrations(): Collection
    {
        return $this->foodAdministrations;
    }

    public function addFoodAdministration(FoodAdministration $foodAdministration): static
    {
        if (!$this->foodAdministrations->contains($foodAdministration)) {
            $this->foodAdministrations->add($foodAdministration);
            $foodAdministration->setAdministeredBy($this);
        }

        return $this;
    }

    public function removeFoodAdministration(FoodAdministration $foodAdministration): static
    {
        if ($this->foodAdministrations->removeElement($foodAdministration)) {
            // set the owning side to null (unless already changed)
            if ($foodAdministration->getAdministeredBy() === $this) {
                $foodAdministration->setAdministeredBy(null);
            }
        }

        return $this;
    }
}
