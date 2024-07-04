<?php

namespace App\Entity;

use App\Entity\Trait\SoftDeletableTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\ImageRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    use TimestampableTrait;

    #[Vich\UploadableField(mapping: 'user_avatar_file', fileNameProperty: 'name', size: 'size', mimeType: 'mimeType', originalName: 'originalName')]
    private ?File $userAvatarFile = null;

    #[Vich\UploadableField(mapping: 'animal_file', fileNameProperty: 'name', size: 'size', mimeType: 'mimeType', originalName: 'originalName')]
    private ?File $animalFile = null;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $originalName = null;

    #[ORM\Column(nullable: true)]
    private ?int $size = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mimeType = null;

    
    /**
     * @var Collection<int, Habitat>
     */
    #[ORM\ManyToMany(targetEntity: Habitat::class, inversedBy: 'images')]
    private Collection $habitats;

    /**
     * @var Collection<int, Animal>
     */
    #[ORM\ManyToMany(targetEntity: Animal::class, inversedBy: 'images')]
    private Collection $animals;

    public function __construct()
    {
        $this->habitats = new ArrayCollection();
        $this->animals = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Habitat>
     */
    public function getHabitats(): Collection
    {
        return $this->habitats;
    }

    public function addHabitat(Habitat $habitat): static
    {
        if (!$this->habitats->contains($habitat)) {
            $this->habitats->add($habitat);
        }

        return $this;
    }

    public function removeHabitat(Habitat $habitat): static
    {
        $this->habitats->removeElement($habitat);

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
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): static
    {
        $this->animals->removeElement($animal);

        return $this;
    }

    public function getUserAvatarFile(): ?File
    {
        return $this->userAvatarFile;
    }

    public function setUserAvatarFile(?File $userAvatarFile): static
    {
        $this->userAvatarFile = $userAvatarFile;

        // unset the owning side of the relation if necessary
        if ($userAvatarFile === null && $this->userAvatarFile !== null) {
            $this->updatedAt = new DateTimeImmutable();
        }

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(?string $originalName): self
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getAnimalFile(): ?File
    {
        return $this->animalFile;
    }

    public function setAnimalFile(?File $animalFile): static
    {
        $this->animalFile = $animalFile;

        // unset the owning side of the relation if necessary
        if ($animalFile === null && $this->animalFile !== null) {
            $this->updatedAt = new DateTimeImmutable();
        }

        return $this;
    }
}
