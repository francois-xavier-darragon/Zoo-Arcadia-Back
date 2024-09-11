<?php

namespace App\Repository;

use App\Entity\Animal;
use App\Service\GenericRepositoryService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Animal>
 */
class AnimalRepository extends ServiceEntityRepository
{
    public function __construct(
        private ManagerRegistry $registry,
        private GenericRepositoryService $genericRepository,
    )
    {
        parent::__construct($registry, Animal::class);
    }

    // method to find a Animal by their identifier (ID)
    public function findAnimalById(int $id): ?Animal
    {
        return $this->genericRepository->findOneBy(['id' => $id], Animal::class);
    }

    // Method to find a Animal by specific criteria
    public function findOneAnimalBy(array $criteria): ?Animal
    {
        return $this->genericRepository->findOneBy($criteria, Animal::class);
    }

    // Method to find all Animal
    public function findAllAnimal(): array
    {
        return $this->genericRepository->findAll(Animal::class);
    }

    // Method to find Animal with paging and sorting
    public function findAnimalBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): array
    {
        return $this->genericRepository->findBy($criteria, Animal::class, $orderBy, $limit, $offset);
    }

    // Method to save a user
    public function saveAnimal(Animal $entity, bool $flush = false)
    {
        $this->genericRepository->save(Animal::class, $entity, $flush);
    }

    // Method to delete a user
    public function removeAnimal(Animal $entity, bool $flush = false)
    {
        $this->genericRepository->remove(Animal::class, $entity, $flush);
    }
}
