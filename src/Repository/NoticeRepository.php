<?php

namespace App\Repository;

use App\Entity\Notice;
use App\Service\GenericRepositoryService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notice>
 */
class NoticeRepository extends ServiceEntityRepository
{
    public function __construct(
        private ManagerRegistry $registry,
        private GenericRepositoryService $genericRepository,
    )
    {
        parent::__construct($registry, Notice::class);
    }

    // method to find a Notice by their identifier (ID)
    public function findNoticeById(int $id): ?Notice
    {
        return $this->genericRepository->findOneBy(['id' => $id], Notice::class);
    }

    // Method to find a Notice by specific criteria
    public function findOneNoticeBy(array $criteria): ?Notice
    {
        return $this->genericRepository->findOneBy($criteria, Notice::class);
    }

    // Method to find all Notice
    public function findAllNotice(): array
    {
        return $this->genericRepository->findAll(Notice::class);
    }

    // Method to find Notice with paging and sorting
    public function findNoticeBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): array
    {
        return $this->genericRepository->findBy($criteria, Notice::class, $orderBy, $limit, $offset);
    }

    // Method to save a user
    public function saveNotice(Notice $entity, bool $flush = false)
    {
        $this->genericRepository->save(Notice::class, $entity, $flush);
    }

    // Method to delete a user
    public function removeNotice(Notice $entity, bool $flush = false)
    {
        $this->genericRepository->remove(Notice::class, $entity, $flush);
    }

    public function countPendingNotices(): int
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT COUNT(id) as count
            FROM notice
            WHERE status = :status
        ';
        
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery(['status' => 'En attente']);

        return (int) $result->fetchOne();
    }
}
