<?php

namespace App\Repository;

use App\Entity\Enclosure;
use App\Service\GenericRepositoryService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Enclosure>
 */
class EnclosureRepository extends ServiceEntityRepository
{
    public function __construct(
        private ManagerRegistry $registry,
        private GenericRepositoryService $genericRepository,
    )
    {
        parent::__construct($registry, Enclosure::class);
    }

   // method to find a Enclosure by their identifier (ID)
   public function findEnclosureById(int $id): ?Enclosure
   {
       return $this->genericRepository->findOneBy(['id' => $id], Enclosure::class);
   }

   // Method to find a Enclosure by specific criteria
   public function findOneEnclosureBy(array $criteria): ?Enclosure
   {
       return $this->genericRepository->findOneBy($criteria, Enclosure::class);
   }

   // Method to find all Enclosure
   public function findAllEnclosure(): array
   {
       return $this->genericRepository->findAll(Enclosure::class);
   }

   // Method to find Enclosure with paging and sorting
   public function findEnclosureBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): array
   {
       return $this->genericRepository->findBy($criteria, Enclosure::class, $orderBy, $limit, $offset);
   }

   // Method to save a user
   public function saveEnclosure(Enclosure $entity, bool $flush = false)
   {
       $this->genericRepository->save(Enclosure::class, $entity, $flush);
   }

   // Method to delete a user
   public function removeEnclosure(Enclosure $entity, bool $flush = false)
   {
       $this->genericRepository->remove(Enclosure::class, $entity, $flush);
   }
}
