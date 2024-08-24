<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Model;

use UserManager\Core\Context\Domain\Exception\ResourceByIdNotFoundException;

interface RepositoryInterface
{
    public const ORDER_ASCENDING = 'ASC';

    public const ORDER_DESCENDING = 'DESC';

    /**
     * @param mixed $id
     *
     * @return ResourceInterface|null
     */
    public function find($id): ?ResourceInterface;

    /**
     * @param mixed $id
     *
     * @return ResourceInterface
     *
     * @throws ResourceByIdNotFoundException
     */
    public function findOrFail($id): ResourceInterface;

    /**
     * @return ResourceInterface[]
     */
    public function findAll(): array;

    /**
     * @param mixed[]       $criteria
     * @param string[]|null $orderBy
     * @param int|null      $limit
     * @param int|null      $offset
     *
     * @return ResourceInterface[]
     *
     * @throws \UnexpectedValueException
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;

    /**
     * @param mixed[]      $criteria
     * @param mixed[]|null $orderBy
     *
     * @return ResourceInterface|null
     */
    public function findOneBy(array $criteria, array $orderBy = null): ?ResourceInterface;

    /**
     * @param ResourceInterface $resource
     */
    public function add(ResourceInterface $resource): void;

    /**
     * @param ResourceInterface $resource
     */
    public function remove(ResourceInterface $resource): void;

    /**
     * @return string
     * @psalm-return class-string<mixed>
     */
    public function getClassName(): string;

    /**
     * @param mixed[]  $criteria
     * @param string[] $sorting
     *
     * @return iterable<ResourceInterface>
     */
    public function createPaginator(array $criteria = [], array $sorting = []): iterable;
}
