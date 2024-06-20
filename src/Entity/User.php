<?php

namespace App\Entity;

use App\Entity\Trait\SoftDeletableTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    use TimestampableTrait;
    use SoftDeletableTrait;

    CONST ROLES = [
        'ROLE_ADMIN'=> 'Administrateur',
        'ROLE_VETERINARY' => 'Vétérinaire',
        'ROLE_WORKER' => 'employé'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column]
    private array $roles = [];

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

    #[ORM\OneToOne(inversedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Image $avatar = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    public function __construct()
    {
        $this->notices = new ArrayCollection();
        $this->veterinaryReports = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->setDeletedAt(null);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
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

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }
}
