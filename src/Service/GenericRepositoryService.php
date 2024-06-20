<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use App\Service\DatabaseService;

class GenericRepositoryService
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private DatabaseService $databaseService
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

    // Method to get table name from entity class name
    private function getTableName(string $entityClass): string
    {
        return $this->entityManager->getClassMetadata($entityClass)->getTableName();
    }

    // Method to save to database
    public function save(string $entityClass, $entity, bool $flush = false): void
    {
        $metaData = $this->entityManager->getClassMetadata($entityClass);
        $tableName = $metaData->getTableName();
        $columns = $metaData->getColumnNames();
        
        $values = [];
    
        foreach ($columns as $column) {
            $fieldName = $metaData->getFieldName($column);
            $getter = 'get' . ucfirst($fieldName);
          
            if (method_exists($entity, $getter)) {
                $value = $entity->$getter();

                if ($fieldName === 'id') {
                    continue; 
                }

                if ($value instanceof \DateTimeImmutable && $value !== null) {
                    $values[$column] = $value->format('Y-m-d H:i:s');
                } elseif (is_array($value) || is_object($value)) {
                    $values[$column] = json_encode($value);
                } elseif ($value == null) {
                    $values[$column] = null;    
                } else {
                    $values[$column] = $value; 
                }
            }
        }
       
        $sqlValues = implode(', ', array_map(function($uniqueValue) {
            return $uniqueValue === null ? 'NULL' : "'" . addslashes($uniqueValue) . "'";
        }, $values));

        $sql = "INSERT INTO $tableName (" . implode(', ', array_keys($values)) . ") VALUES ($sqlValues)";

        if ($flush) { 
            $this->databaseService->selectDatabase('arcadia');
            $this->databaseService->query($sql);
        }
    }

    // Method to delete in database
    public function remove(string $entityClass, $entity, bool $flush = false): void
    {
        $metaData = $this->entityManager->getClassMetadata($entityClass);
        $tableName = $metaData->getTableName();
        $idColumn = $metaData->getSingleIdentifierColumnName();
        $idGetter = 'get' . ucfirst($metaData->getFieldName($idColumn));
        $id = $entity->$idGetter();

        if ($id !== null) {
            $sql = "DELETE FROM $tableName WHERE $idColumn = ?";

            if ($flush) {
                $this->databaseService->selectDatabase('arcadia'); 
                $this->databaseService->query($sql, [$id]);
            }

        } else {
            throw new \InvalidArgumentException("Cannot delete entity without valid ID.");
        }
    }
}
