<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use UserManager\Core\Common\Pager\Adapter\QueryAdapter;
use UserManager\Core\Common\Pager\Pager;
use UserManager\Core\Context\Domain\Exception\ResourceByIdNotFoundException;
use UserManager\Core\Context\Domain\Model\RepositoryReadOnlyInterface;
use UserManager\Core\Context\Domain\Model\ResourceInterface;

abstract class DoctrineReadOnlyRepository implements RepositoryReadOnlyInterface
{
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @var ClassMetadata
     */
    private $class;

    public function __construct(EntityManager $entityManager)
    {
        $this->setManager($entityManager);
        $this->setRepository($entityManager);
        $this->setClass($entityManager);
    }

    private function setRepository(EntityManager $entityManager): void
    {
        $repository = $entityManager->getRepository($this->getClassName());

        $this->repository = $repository;
    }

    protected function repository(): EntityRepository
    {
        return $this->repository;
    }

    private function setManager(EntityManager $entityManager): void
    {
        $this->manager = $entityManager;
    }

    protected function manager(): EntityManager
    {
        return $this->manager;
    }

    private function setClass(EntityManager $entityManager): void
    {
        $this->class = $entityManager->getClassMetadata($this->getClassName());
    }

    protected function class(): ClassMetadata
    {
        return $this->class;
    }

    public function find($id, ?int $lockMode = null, ?int $lockVersion = null): ?ResourceInterface
    {
        /** @var ResourceInterface|null $result */
        /** @noinspection PhpUnnecessaryLocalVariableInspection */
        $result = $this->repository()->find($id, $lockMode, $lockVersion);

        return $result;
    }

    public function findOrFail($id): ResourceInterface
    {
        if (null === $resource = $this->find($id)) {
            throw new ResourceByIdNotFoundException((string) $id);
        }

        return $resource;
    }

    public function findAll(): array
    {
        return $this->repository()->findAll();
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->repository()->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?ResourceInterface
    {
        /** @var ResourceInterface|null $result */
        /** @noinspection PhpUnnecessaryLocalVariableInspection */
        $result = $this->repository()->findOneBy($criteria, $orderBy);

        return $result;
    }

    abstract public function getClassName(): string;

    /**
     * @param string      $alias
     * @param string|null $indexBy
     */
    public function createQueryBuilder(string $alias, string $indexBy = null): QueryBuilder
    {
        return $this->repository()->createQueryBuilder($alias, $indexBy);
    }

    public function createPaginator(array $criteria = [], array $sorting = []): iterable
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $this->applyCriteria($queryBuilder, $criteria);
        $this->applySorting($queryBuilder, $sorting);

        return $this->getPaginator($queryBuilder);
    }

    /**
     * @return Pager<ResourceInterface>
     */
    protected function getPaginator(QueryBuilder $queryBuilder): Pager
    {
        return new Pager(new QueryAdapter($queryBuilder, false, false));
    }

    /**
     * @param mixed[] $criteria
     *
     * @see createPaginator
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = []): void
    {
        foreach ($criteria as $property => $value) {
            if (! in_array(
                $property,
                array_merge($this->class()->getAssociationNames(), $this->class()->getFieldNames()),
                true
            )) {
                continue;
            }

            $name = $this->getPropertyName($property);

            if (null === $value) {
                $queryBuilder->andWhere($queryBuilder->expr()->isNull($name));
            } elseif (is_array($value)) {
                $queryBuilder->andWhere($queryBuilder->expr()->in($name, $value));
            } elseif ('' !== $value) {
                $parameter = str_replace('.', '_', $property);
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->eq($name, ':' . $parameter))
                    ->setParameter($parameter, $value);
            }
        }
    }

    /**
     * @param string[]     $sorting
     *
     * @see createPaginator
     */
    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = []): void
    {
        foreach ($sorting as $property => $order) {
            if (! in_array(
                $property,
                array_merge($this->class()->getAssociationNames(), $this->class()->getFieldNames()),
                true
            )) {
                continue;
            }

            if (! empty($order)) {
                $queryBuilder->addOrderBy($this->getPropertyName($property), $order);
            }
        }
    }

    /**
     * @see applyCriteria
     * @see applySorting
     */
    protected function getPropertyName(string $name): string
    {
        if (0 === preg_match('/^o\./', $name)) {
            return 'o' . '.' . $name;
        }

        return $name;
    }
}
