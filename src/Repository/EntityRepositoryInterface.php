<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\EntityInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;

interface EntityRepositoryInterface
{
    /**@return EntityInterface[] */
    public function findAll();

    /**@return EntityInterface[] */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    public function getEntityType(): string;

    public function add(EntityInterface $entity, bool $flush = true): void;

    public function remove(EntityInterface $entity, bool $flush = true): void;

    public function find(int $id, int $lockMode = null, int $lockVersion = null);

    public function count(array $criteria);

    public function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null, string $alias = 'e'): QueryBuilder;

    public function findOneBy(array $criteria, array $orderBy = null);

    public function findAllWithSearch(?string $search, ?bool $showDeleted = false): array;

    /**@return QueryBuilder */
    public function createQueryBuilder(string $alias, string $indexBy = null);

    public function getQueryBuilder(array $criteria, array $orderBy = null, $limit = null, $offset = null, string $alias = 'e'): QueryBuilder;

    public function applyOrderBy(QueryBuilder $qb, array $orderBy, string $alias = 'e'): void;

    public function persist(object $object, bool $flush = true): void;

    public function flush(): void;

    public function createQueryBuilderWithoutSelect($alias, $indexBy = null): QueryBuilder;

    public function toIterable(array $criteria = [], array $orderBy = null, $limit = null, $offset = null): iterable;
}
