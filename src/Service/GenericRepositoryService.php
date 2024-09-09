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
        
        // Executing the query and retrieving the result
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
    public function getTableName(string $entityClass): string
    {
        return $this->entityManager->getClassMetadata($entityClass)->getTableName();
    }

    // Method to save to database
    public function save(string $entityClass, $entity, bool $flush = false): void
    {
        
        if ($this->hasUploadableFields($entity)) {
            $this->saveOrm($entity, $flush);

            return;
        }

        $this->databaseService->selectDatabase('arcadia');

        $metaData = $this->entityManager->getClassMetadata($entityClass);
        $tableName = $metaData->getTableName();
        $columns = $metaData->getColumnNames();
    
        $values = [];
        $idValue = null;
        foreach ($columns as $column) {
            $fieldName = $metaData->getFieldName($column);
            $getter = 'get' . ucfirst($fieldName);
          
            if (method_exists($entity, $getter)) {
                $value = $entity->$getter();

                if ($fieldName === 'id') {
                    $idValue = $value;
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

        $connection = $this->databaseService->getConnection();

    if ($idValue !== null) {
        // Check if the entity already exists
        $checkSql = "SELECT COUNT(*) FROM $tableName WHERE id = :id";
        $stmt = $connection->prepare($checkSql);
        $stmt->execute(['id' => $idValue]);
        $exists = $stmt->fetchColumn();

            if ($exists) {
                // Update existing data
                $updateValues = [];
                foreach ($values as $column => $value) {
                    $updateValues[] = "$column = :$column";
                }

                $updateSql = "UPDATE $tableName SET " . implode(', ', $updateValues) . " WHERE id = :id";
                $stmt = $connection->prepare($updateSql);
                $values['id'] = $idValue;
                if ($flush) {
                   $stmt->execute($values);
                }
            }

        } else {
            //Insert new data
            $sqlValues = implode(', ', array_map(function($uniqueValue) {
            return $uniqueValue === null ? 'NULL' : "'" . addslashes($uniqueValue) . "'";
            }, $values));
    
            $sql = "INSERT INTO $tableName (" . implode(', ', array_keys($values)) . ") VALUES ($sqlValues)";
            if ($flush) {
                $this->databaseService->query($sql);
            }
        }
    }

    // Method to delete in database
    public function remove(string $entityClass, $entity, bool $flush = false): void
    {
        $this->databaseService->selectDatabase('arcadia');
        
        $metaData = $this->entityManager->getClassMetadata($entityClass);
        $tableName = $metaData->getTableName();
        $idColumn = $metaData->getSingleIdentifierColumnName();
        $idGetter = 'get' . ucfirst($metaData->getFieldName($idColumn));
        $id = $entity->$idGetter();

        if ($id !== null) {
            $sql = "DELETE FROM $tableName WHERE $idColumn = ?";

            if ($flush) {
                $this->databaseService->query($sql, [$id]);
            }

        } else {
            throw new \InvalidArgumentException("Cannot delete entity without valid ID.");
        }
    }

    private function hasUploadableFields($entity): bool
    {
        $reflectionClass = new \ReflectionClass($entity);
        
        foreach ($reflectionClass->getProperties() as $property) {
            foreach ($property->getAttributes() as $attribute) {
                if (in_array($attribute->getName(), [
                    'Vich\UploaderBundle\Mapping\Annotation\UploadableField',
                    'Symfony\Component\Validator\Constraints\File',
                    'Symfony\Component\Validator\Constraints\Image'
                ])) {
                    return true;
                }
            }

            $this->getRelatedEntity($entity, $property->getName());

            return true;
        }
         return false;
    }

    private function getRelatedEntity($entity, $propertyName)
    {
        
        $getter = 'get' . ucfirst($propertyName);
      
        if (method_exists($entity, $getter)) {
            return $entity->$getter();
        } else {
            return false;
        }
    
    }
    public function saveOrm($entity, $flush): void
    {
        $this->entityManager->persist($entity);

        if ($flush) {
            $this->entityManager->flush();
        }
    }
}
