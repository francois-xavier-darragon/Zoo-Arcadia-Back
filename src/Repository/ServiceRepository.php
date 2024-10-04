<?php

namespace App\Repository;

use App\Entity\Service;
use App\Service\GenericRepositoryService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Service>
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(
        private ManagerRegistry $registry,
        private GenericRepositoryService $genericRepository,
    )
    {
        parent::__construct($registry, Service::class);
    }

    // method to find a Service by their identifier (ID)
    public function findServiceById(int $id): ?Service
    {
        return $this->genericRepository->findOneBy(['id' => $id], Service::class);
    }

    // Method to find a Service by specific criteria
    public function findOneServiceBy(array $criteria): ?Service
    {
        return $this->genericRepository->findOneBy($criteria, Service::class);
    }

    // Method to find all Service
    public function findAllService(): array
    {
        return $this->genericRepository->findAll(Service::class);
    }

    // Method to find Service with paging and sorting
    public function findServiceBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): array
    {
        return $this->genericRepository->findBy($criteria, Service::class, $orderBy, $limit, $offset);
    }

    // Method to save a user
    public function saveService(Service $entity, bool $flush = false)
    {
        $this->genericRepository->save(Service::class, $entity, $flush);
    }

    // Method to delete a user
    public function removeService(Service $entity, bool $flush = false)
    {
        $this->genericRepository->remove(Service::class, $entity, $flush);
    }
}
