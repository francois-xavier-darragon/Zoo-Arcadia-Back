<?php

namespace App\Repository;

use App\Entity\Image;
use App\Service\GenericRepositoryService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Image>
 */
class ImageRepository extends ServiceEntityRepository
{
    public function __construct(
        private ManagerRegistry $registry,
        private GenericRepositoryService $genericRepository,
    )
    {
        parent::__construct($registry, Image::class);
    }

    // method to find a Image by their identifier (ID)
    public function findImageById(int $id): ?Image
    {
        return $this->genericRepository->findOneBy(['id' => $id], Image::class);
    }

    // Method to find a Image by specific criteria
    public function findOneImageBy(array $criteria): ?Image
    {
        return $this->genericRepository->findOneBy($criteria, Image::class);
    }

    // Method to find all Image
    public function findAllImage(): array
    {
        return $this->genericRepository->findAll(Image::class);
    }

    // Method to find Image with paging and sorting
    public function findImageBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): array
    {
        return $this->genericRepository->findBy($criteria, Image::class, $orderBy, $limit, $offset);
    }

    // Method to save a user
    public function saveImage(Image $entity, bool $flush = false)
    {
        $this->genericRepository->save(Image::class, $entity, $flush);
    }

    // Method to delete a user
    public function removeImage(Image $entity, bool $flush = false)
    {
        $this->genericRepository->remove(Image::class, $entity, $flush);
    }
}
