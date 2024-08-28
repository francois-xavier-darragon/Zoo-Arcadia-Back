<?php

namespace App\Repository;

use App\Entity\Habitat;
use App\Service\GenericRepositoryService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Habitat>
 */
class HabitatRepository extends ServiceEntityRepository
{
    public function __construct(
        private ManagerRegistry $registry,
        private GenericRepositoryService $genericRepository,
    )
    {
        parent::__construct($registry, Habitat::class);
    }

    // method to find a Habitat by their identifier (ID)
    public function findHabitatById(int $id): ?Habitat
    {
        return $this->genericRepository->findOneBy(['id' => $id], Habitat::class);
    }

    // Method to find a Habitat by specific criteria
    public function findOneHabitatBy(array $criteria): ?Habitat
    {
        return $this->genericRepository->findOneBy($criteria, Habitat::class);
    }

    // Method to find all Habitat
    public function findAllHabitat(): array
    {
        return $this->genericRepository->findAll(Habitat::class);
    }

    // Method to find Habitat with paging and sorting
    public function findHabitatBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): array
    {
        return $this->genericRepository->findBy($criteria, Habitat::class, $orderBy, $limit, $offset);
    }

    // Method to save a user
    public function saveHabitat(Habitat $entity, bool $flush = false)
    {
        $this->genericRepository->save(Habitat::class, $entity, $flush);
    }

    // Method to delete a user
    public function removeHabitat(Habitat $entity, bool $flush = false)
    {
        $this->genericRepository->remove(Habitat::class, $entity, $flush);
    }
}
