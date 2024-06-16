<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

class GenericRepositoryService
{

    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {}

    public function findOneBy(array $criteria, string $entityClass): ?object
    {
        $rsm = new ResultSetMappingBuilder($this->entityManager);
        $rsm->addRootEntityFromClassMetadata($entityClass, 'alias');

        // Construction of the SQL query
        $sql = 'SELECT * FROM ' . $this->getTableName($entityClass) . ' alias WHERE ';
        $params = [];
        foreach ($criteria as $key => $value) {
            $params[] = sprintf('alias.%s = :%s', $key, $key);
        }
        $sql .= implode(' AND ', $params) . ' LIMIT 1';

        // Creating the native query with the ResultSetMappingBuilder
        $query = $this->entityManager->createNativeQuery($sql, $rsm);

        // Setting parameters
        foreach ($criteria as $key => $value) {
            $query->setParameter($key, $value);
        }

        // Executing the query and retrieving the result
        $result = $query->getOneOrNullResult();

        // If no results are found, return null
        return $result ?: null;
    }

    public function findAll(string $entityClass): array
    {
        $rsm = new ResultSetMappingBuilder($this->entityManager);
        $rsm->addRootEntityFromClassMetadata($entityClass, 'alias');

        // Construction of the SQL query
        $sql = 'SELECT * FROM ' . $this->getTableName($entityClass);

        // Creating the native query with the ResultSetMappingBuilder
        $query = $this->entityManager->createNativeQuery($sql, $rsm);

        // Exécution de la requête et récupération du résultat
        return $query->getResult();
    }

    public function findBy(array $criteria, string $entityClass, array $orderBy = null, int $limit = null, int $offset = null): array
    {
        $rsm = new ResultSetMappingBuilder($this->entityManager);
        $rsm->addRootEntityFromClassMetadata($entityClass, 'alias');

        // Construction of the SQL query
        $sql = 'SELECT * FROM ' . $this->getTableName($entityClass) . ' alias WHERE ';
        $params = [];
        foreach ($criteria as $key => $value) {
            $params[] = sprintf('alias.%s = :%s', $key, $key);
        }
        $sql .= implode(' AND ', $params);

        if ($orderBy) {
            $orderParams = [];
            foreach ($orderBy as $key => $value) {
                $orderParams[] = sprintf('alias.%s %s', $key, $value);
            }
            $sql .= ' ORDER BY ' . implode(', ', $orderParams);
        }

        if ($limit !== null) {
            $sql .= ' LIMIT ' . $limit;
        }

        if ($offset !== null) {
            $sql .= ' OFFSET ' . $offset;
        }

        // Creating the native query with the ResultSetMappingBuilder
        $query = $this->entityManager->createNativeQuery($sql, $rsm);

        // Setting parameters
        foreach ($criteria as $key => $value) {
            $query->setParameter($key, $value);
        }

        // Executing the query and retrieving the result
        return $query->getResult();
    }

    // Méthode pour obtenir le nom de la table à partir du nom de classe de l'entité
    private function getTableName(string $entityClass): string
    {
        return $this->entityManager->getClassMetadata($entityClass)->getTableName();
    }
}
