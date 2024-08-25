<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Model;

use UserManager\Core\Context\Domain\Exception\ResourceByIdNotFoundException;

interface RepositoryReadOnlyInterface
{
    public const ORDER_ASCENDING = 'ASC';

    public const ORDER_DESCENDING = 'DESC';

    /**
     * @param mixed $id
     */
    public function find($id): ?ResourceInterface;

    /**
     * @param mixed $id
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
     *
     * @return ResourceInterface[]
     *
     * @throws \UnexpectedValueException
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;

    /**
     * @param mixed[]      $criteria
     * @param mixed[]|null $orderBy
     */
    public function findOneBy(array $criteria, array $orderBy = null): ?ResourceInterface;

    /**
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
