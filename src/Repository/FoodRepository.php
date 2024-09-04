<?php

namespace App\Repository;

use App\Entity\Food;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Food>
 */
class FoodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Food::class);
    }

    // method to find a Food by their identifier (ID)
    public function findFoodById(int $id): ?Food
    {
        return $this->genericRepository->findOneBy(['id' => $id], Food::class);
    }

    // Method to find a Food by specific criteria
    public function findOneFoodBy(array $criteria): ?Food
    {
        return $this->genericRepository->findOneBy($criteria, Food::class);
    }

    // Method to find all Food
    public function findAllFood(): array
    {
        return $this->genericRepository->findAll(Food::class);
    }

    // Method to find Food with paging and sorting
    public function findFoodBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): array
    {
        return $this->genericRepository->findBy($criteria, Food::class, $orderBy, $limit, $offset);
    }

    // Method to save a user
    public function saveFood(Food $entity, bool $flush = false)
    {
        $this->genericRepository->save(Food::class, $entity, $flush);
    }

    // Method to delete a user
    public function removeFood(Food $entity, bool $flush = false)
    {
        $this->genericRepository->remove(Food::class, $entity, $flush);
    }
}
