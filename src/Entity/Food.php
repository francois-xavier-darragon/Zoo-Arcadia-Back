<?php

namespace App\Entity;

use App\Entity\Trait\SoftDeletableTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\FoodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FoodRepository::class)]
class Food
{
    use TimestampableTrait;
    use SoftDeletableTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $quantity = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $mealTime = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $foodType = null;

    #[ORM\ManyToOne(inversedBy: 'foods')]
    private ?Animal $animal = null;

    #[ORM\ManyToOne(inversedBy: 'prescribedFoods')]
    private ?User $prescribedBy = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $instructions = null;

    /**
     * @var Collection<int, FoodAdministration>
     */
    #[ORM\ManyToMany(targetEntity: FoodAdministration::class, inversedBy: 'food')]
    #[ORM\JoinTable(name: 'food_administration_link')]
    private Collection $foodAdministrations;

    public function __construct()
    {
        $this->foodAdministrations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(?float $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getMealTime(): ?\DateTimeImmutable
    {
        return $this->mealTime;
    }

    public function setMealTime(?\DateTimeImmutable $mealTime): static
    {
        $this->mealTime = $mealTime;

        return $this;
    }

    public function getFoodType(): ?string
    {
        return $this->foodType;
    }

    public function setFoodType(?string $foodType): static
    {
        $this->foodType = $foodType;

        return $this;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): static
    {
        $this->animal = $animal;

        return $this;
    }

    public function getPrescribedBy(): ?User
    {
        return $this->prescribedBy;
    }

    public function setPrescribedBy(?User $prescribedBy): static
    {
        $this->prescribedBy = $prescribedBy;

        return $this;
    }

    public function getInstructions(): ?string
    {
        return $this->instructions;
    }

    public function setInstructions(?string $instructions): static
    {
        $this->instructions = $instructions;

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
        }

        return $this;
    }

    public function removeFoodAdministration(FoodAdministration $foodAdministration): static
    {
        $this->foodAdministrations->removeElement($foodAdministration);

        return $this;
    }
}
