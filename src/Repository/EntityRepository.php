<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\EntityInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

abstract class EntityRepository extends ServiceEntityRepository implements EntityRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        protected EventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($registry, $this->getEntityType());
    }

    public function add(EntityInterface $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);

        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(EntityInterface $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);

        if ($flush) {
            $this->_em->flush();
        }
    }

    public function persist(object $object, bool $flush = true): void
    {
        $this->_em->persist($object);

        if ($flush) {
            $this->flush();
        }
    }

    public function flush(): void
    {
        $this->_em->flush();
    }

    public function createQueryBuilderWithoutSelect($alias, $indexBy = null): QueryBuilder
    {
        return $this->_em->createQueryBuilder()
            ->from($this->_entityName, $alias, $indexBy);
    }

    public function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null, string $alias = 'e'): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder($alias);
    }

    public function findAllWithSearch(?string $search, ?bool $showDeleted = false): array
    {
        return $this->findAllWithSearchQuery($search, $showDeleted)->getResult();
    }

    public function findAllWithSearchQuery(?string $search, ?bool $showDeleted = false): Query
    {
        return $this->getOrCreateQueryBuilder()->getQuery();
    }

    /**
     * @param mixed|null $limit
     * @param mixed|null $offset
     *
     * @throws Query\QueryException
     */
    public function getQueryBuilder(
        array $criteria,
        array $orderBy = null,
        $limit = null,
        $offset = null,
        string $alias = 'e'
    ): QueryBuilder {
        $qb = $this->createQueryBuilder($alias)
            ->addCriteria($this->getCriteria($criteria, $alias))
        ;

        if ($orderBy) {
            $this->applyOrderBy($qb, $orderBy, $alias);
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        if ($limit) {
            $qb->setFirstResult($offset);
        }

        return $qb;
    }

    public function applyOrderBy(QueryBuilder $qb, array $orderBy, string $alias = 'e'): void
    {
        foreach ($orderBy as $sort => $order) {
            $qb->addOrderBy($alias.'.'.$sort, $order);
        }
    }

    /**
     * @throws QueryException
     */
    public function toIterable(array $criteria = [], array $orderBy = null, $limit = null, $offset = null): iterable
    {
        $qb = $this->getQueryBuilder($criteria, $orderBy, $limit, $offset);

        foreach ($qb->getQuery()->toIterable() as $value) {
            yield $value;
        }
    }

    abstract public function getEntityType(): string;
}
