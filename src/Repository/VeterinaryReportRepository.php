<?php

namespace App\Repository;

use App\Entity\VeterinaryReport;
use App\Service\GenericRepositoryService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VeterinaryReport>
 */
class VeterinaryReportRepository extends ServiceEntityRepository
{
    public function __construct(
        private ManagerRegistry $registry,
        private GenericRepositoryService $genericRepository,
    )
    {
        parent::__construct($registry, VeterinaryReport::class);
    }

    // method to find a VeterinaryReport by their identifier (ID)
    public function findVeterinaryReportById(int $id): ?VeterinaryReport
    {
        return $this->genericRepository->findOneBy(['id' => $id], VeterinaryReport::class);
    }

    // Method to find a VeterinaryReport by specific criteria
    public function findOneVeterinaryReportBy(array $criteria): ?VeterinaryReport
    {
        return $this->genericRepository->findOneBy($criteria, VeterinaryReport::class);
    }

    // Method to find all VeterinaryReport
    public function findAllVeterinaryReport(): array
    {
        return $this->genericRepository->findAll(VeterinaryReport::class);
    }

    // Method to find VeterinaryReport with paging and sorting
    public function findVeterinaryReportBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): array
    {
        return $this->genericRepository->findBy($criteria, VeterinaryReport::class, $orderBy, $limit, $offset);
    }

    // Method to save a user
    public function saveVeterinaryReport(VeterinaryReport $entity, bool $flush = false)
    {
        $this->genericRepository->save(VeterinaryReport::class, $entity, $flush);
    }

    // Method to delete a user
    public function removeVeterinaryReport(VeterinaryReport $entity, bool $flush = false)
    {
        $this->genericRepository->remove(VeterinaryReport::class, $entity, $flush);
    }
}
