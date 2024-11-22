<?php

namespace App\Entity;

use App\Entity\Trait\SoftDeletableTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\FoodAdministrationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FoodAdministrationRepository::class)]
class FoodAdministration
{
    use TimestampableTrait;
    use SoftDeletableTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'foodAdministrations')]
    private ?User $administeredBy = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $administrationDate = null;

    #[ORM\Column(nullable: true)]
    private ?float $quantityAdministered = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notes = null;

    /**
     * @var Collection<int, Food>
     */
    #[ORM\ManyToMany(targetEntity: Food::class, mappedBy: 'foodAdministrations')]
    private Collection $food;

    public function __construct()
    {
        $this->food = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdministeredBy(): ?User
    {
        return $this->administeredBy;
    }

    public function setAdministeredBy(?User $administeredBy): static
    {
        $this->administeredBy = $administeredBy;

        return $this;
    }

    public function getAdministrationDate(): ?\DateTimeImmutable
    {
        return $this->administrationDate;
    }

    public function setAdministrationDate(?\DateTimeImmutable $administrationDate): static
    {
        $this->administrationDate = $administrationDate;

        return $this;
    }

    public function getQuantityAdministered(): ?float
    {
        return $this->quantityAdministered;
    }

    public function setQuantityAdministered(?float $quantityAdministered): static
    {
        $this->quantityAdministered = $quantityAdministered;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * @return Collection<int, Food>
     */
    public function getFood(): Collection
    {
        return $this->food;
    }

    public function addFood(Food $food): static
    {
        if (!$this->food->contains($food)) {
            $this->food->add($food);
            $food->addFoodAdministration($this);
        }

        return $this;
    }

    public function removeFood(Food $food): static
    {
        if ($this->food->removeElement($food)) {
            $food->removeFoodAdministration($this);
        }

        return $this;
    }
}
