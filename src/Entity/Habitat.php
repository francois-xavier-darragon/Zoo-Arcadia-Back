<?php

namespace App\Entity;

use App\Entity\Trait\SoftDeletableTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\HabitatRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HabitatRepository::class)]
class Habitat
{
    use TimestampableTrait;
    use SoftDeletableTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Animal>
     */
    #[ORM\OneToMany(targetEntity: Animal::class, mappedBy: 'habitat', orphanRemoval: true, cascade: ['persist'])]
    private Collection $animals;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'habitat', cascade: ['persist'])]
    private Collection $images;

    /**
     * @var Collection<int, Enclosure>
     */
    #[ORM\OneToMany(targetEntity: Enclosure::class, mappedBy: 'habitat', orphanRemoval: true, cascade: ['persist'])]
    private Collection $enclosures;

    public function __construct()
    {
        $this->animals = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->enclosures = new ArrayCollection();
        $this->setDeletedAt(null);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Animal>
     */
    public function getAnimals(): Collection
    {
        return $this->animals;
    }

    public function addAnimal(Animal $animal): static
    {
        if (!$this->animals->contains($animal)) {
            $this->animals->add($animal);
            $animal->setHabitat($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): static
    {
        if ($this->animals->removeElement($animal)) {
            // set the owning side to null (unless already changed)
            if ($animal->getHabitat() === $this) {
                $animal->setHabitat(null);
            }
        }

        return $this;
    }

    // /**
    //  * @return Collection<int, Image>
    //  */
    // public function getImages(): Collection
    // {
    //     return $this->images;
    // }

    // public function addImage(Image $image): static
    // {
    //     if (!$this->images->contains($image)) {
    //         $this->images->add($image);
    //         $image->addHabitat($this);
    //     }

    //     return $this;
    // }

    // public function removeImage(Image $image): static
    // {
    //     if ($this->images->removeElement($image)) {
    //         $image->removeHabitat($this);
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setHabitat($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getHabitat() === $this) {
                $image->setHabitat(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Enclosure>
     */
    public function getEnclosures(): Collection
    {
        return $this->enclosures;
    }

    public function addEnclosure(Enclosure $enclosure): static
    {
        if (!$this->enclosures->contains($enclosure)) {
            $this->enclosures->add($enclosure);
            $enclosure->setHabitat($this);
        }

        return $this;
    }

    public function removeEnclosure(Enclosure $enclosure): static
    {
        if ($this->enclosures->removeElement($enclosure)) {
            // set the owning side to null (unless already changed)
            if ($enclosure->getHabitat() === $this) {
                $enclosure->setHabitat(null);
            }
        }

        return $this;
    }
}
