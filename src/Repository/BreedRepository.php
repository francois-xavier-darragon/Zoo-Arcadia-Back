<?php

namespace App\Repository;

use App\Entity\Breed;
use App\Service\GenericRepositoryService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Breed>
 */
class BreedRepository extends ServiceEntityRepository
{
    public function __construct(
        private ManagerRegistry $registry,
        private GenericRepositoryService $genericRepository,
    )
    {
        parent::__construct($registry, Breed::class);
    }

    // method to find a Breed by their identifier (ID)
    public function findBreedById(int $id): ?Breed
    {
        return $this->genericRepository->findOneBy(['id' => $id], Breed::class);
    }

    // Method to find a Breed by specific criteria
    public function findOneBreedBy(array $criteria): ?Breed
    {
        return $this->genericRepository->findOneBy($criteria, Breed::class);
    }

    // Method to find all Breed
    public function findAllBreed(): array
    {
        return $this->genericRepository->findAll(Breed::class);
    }

    // Method to find Breed with paging and sorting
    public function findBreedBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): array
    {
        return $this->genericRepository->findBy($criteria, Breed::class, $orderBy, $limit, $offset);
    }

    // Method to save a user
    public function saveBreed(Breed $entity, bool $flush = false)
    {
        $this->genericRepository->save(Breed::class, $entity, $flush);
    }

    // Method to delete a user
    public function removeBreed(Breed $entity, bool $flush = false)
    {
        $this->genericRepository->remove(Breed::class, $entity, $flush);
    }
}
