<?php

namespace App\Repository;

use App\Entity\User;
use App\Service\GenericRepositoryService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(
        private ManagerRegistry $registry,
        private GenericRepositoryService $genericRepository,
    )
    {
        parent::__construct($registry, User::class);
    }

    // method to find a User by their identifier (ID)
    public function findUserById(int $id): ?User
    {
        return $this->genericRepository->findOneBy(['id' => $id], User::class);
    }

    // Method to find a User by specific criteria
    public function findOneUserBy(array $criteria): ?User
    {
        return $this->genericRepository->findOneBy($criteria, User::class);
    }

    // Method to find all User
    public function findAllUser(): array
    {
        return $this->genericRepository->findAll(User::class);
    }

    // Method to find User with paging and sorting
    public function findUserBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): array
    {
        return $this->genericRepository->findBy($criteria, User::class, $orderBy, $limit, $offset);
    }

    // Method to save a user
    public function saveUser(User $entity, bool $flush = false)
    {
        $this->genericRepository->save(User::class, $entity, $flush);
    }

    // Method to delete a user
    public function removeUser(User $entity, bool $flush = false)
    {
        $this->genericRepository->remove(User::class, $entity, $flush);
    }

}
