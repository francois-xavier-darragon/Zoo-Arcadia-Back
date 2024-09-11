<?php

namespace App\Entity;

use App\Entity\Trait\SoftDeletableTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\ImageRepository;
use DateTimeImmutable;
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

    #[Vich\UploadableField(mapping: 'habitat_file', fileNameProperty: 'name', size: 'size', mimeType: 'mimeType', originalName: 'originalName')]
    private ?File $habitatFile = null;

    #[Vich\UploadableField(mapping: 'enclosure_file', fileNameProperty: 'name', size: 'size', mimeType: 'mimeType', originalName: 'originalName')]
    private ?File $enclosureFile = null;

    #[Vich\UploadableField(mapping: 'service_file', fileNameProperty: 'name', size: 'size', mimeType: 'mimeType', originalName: 'originalName')]
    private ?File $serviceFile = null;
    
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

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Animal $animal = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Habitat $habitat = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Service $service = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Enclosure $enclosure = null;

    public function __construct()
    {
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

    public function getHabitatFile(): ?File
    {
        return $this->habitatFile;
    }

    public function setHabitatFile(?File $habitatFile): static
    {
        $this->habitatFile = $habitatFile;

        // unset the owning side of the relation if necessary
        if ($habitatFile === null && $this->habitatFile !== null) {
            $this->updatedAt = new DateTimeImmutable();
        }

        return $this;
    }

    public function getEnclosureFile(): ?File
    {
        return $this->enclosureFile;
    }

    public function setEnclosureFile(?File $enclosureFile): static
    {
        $this->enclosureFile = $enclosureFile;

        // unset the owning side of the relation if necessary
        if ($enclosureFile === null && $this->enclosureFile !== null) {
            $this->updatedAt = new DateTimeImmutable();
        }

        return $this;
    }
   
    public function getServiceFile(): ?File
    {
        return $this->serviceFile;
    }

    public function setServiceFile(?File $serviceFile): static
    {
        $this->serviceFile = $serviceFile;

        // unset the owning side of the relation if necessary
        if ($serviceFile === null && $this->serviceFile !== null) {
            $this->updatedAt = new DateTimeImmutable();
        }

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

    public function getHabitat(): ?Habitat
    {
        return $this->habitat;
    }

    public function setHabitat(?Habitat $habitat): static
    {
        $this->habitat = $habitat;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): static
    {
        $this->service = $service;

        return $this;
    }

    public function getEnclosure(): ?Enclosure
    {
        return $this->enclosure;
    }

    public function setEnclosure(?Enclosure $enclosure): static
    {
        $this->enclosure = $enclosure;

        return $this;
    }
}
