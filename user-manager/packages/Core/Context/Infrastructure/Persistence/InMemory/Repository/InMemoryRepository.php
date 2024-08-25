<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Infrastructure\Persistence\InMemory\Repository;

use ArrayObject;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use UserManager\Core\Common\Exception\InvalidArgumentException;
use UserManager\Core\Common\Exception\Model\ExistingResourceException;
use UserManager\Core\Common\Exception\UnexpectedTypeException;
use UserManager\Core\Common\Pager\Adapter\ArrayAdapter;
use UserManager\Core\Common\Pager\Pager;
use UserManager\Core\Context\Domain\Exception\ResourceByIdNotFoundException;
use UserManager\Core\Context\Domain\Model\RepositoryInterface;
use UserManager\Core\Context\Domain\Model\ResourceInterface;

abstract class InMemoryRepository implements RepositoryInterface
{
    /**
     * @var PropertyAccessorInterface
     */
    protected $accessor;

    /**
     * @var ArrayObject<int|string, object>
     */
    protected $arrayObject;

    /**
     * @var string
     */
    protected $interface;

    /**
     * @throws \InvalidArgumentException
     * @throws UnexpectedTypeException
     */
    public function __construct()
    {
        $interfaces = class_implements($this->getClassName());

        if (false === $interfaces || false === in_array(ResourceInterface::class, $interfaces, true)) {
            throw new UnexpectedTypeException($this->getClassName(), ResourceInterface::class);
        }

        $this->interface = $this->getClassName();
        $this->accessor =
            PropertyAccess::createPropertyAccessorBuilder()
                ->getPropertyAccessor();
        $this->arrayObject = new ArrayObject();
    }

    /**
     * @throws ExistingResourceException
     * @throws UnexpectedTypeException
     */
    public function add(ResourceInterface $resource): void
    {
        if (! $resource instanceof $this->interface) {
            throw new UnexpectedTypeException($resource, $this->interface);
        }

        if (in_array($resource, $this->findAll(), true)) {
            throw new ExistingResourceException();
        }

        $this->arrayObject->append($resource);
    }

    public function remove(ResourceInterface $resource): void
    {
        $newResources = array_filter(
            $this->findAll(),
            static function ($object) use ($resource) {
                return $object !== $resource;
            }
        );

        $this->arrayObject->exchangeArray($newResources);
    }

    /**
     * @param mixed $id
     */
    public function find($id): ?ResourceInterface
    {
        return $this->findOneBy([
            'id' => $id,
        ]);
    }

    /**
     * @param mixed $id
     */
    public function findOrFail($id): ResourceInterface
    {
        if (null === $resource = $this->find($id)) {
            throw new ResourceByIdNotFoundException((string) $id);
        }

        return $resource;
    }

    /**
     * @return ResourceInterface[]
     */
    public function findAll(): array
    {
        return $this->arrayObject->getArrayCopy();
    }

    /**
     * @param mixed[]       $criteria
     * @param string[]|null $orderBy
     *
     * @return ResourceInterface[]
     *
     * @throws \UnexpectedValueException
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        $results = $this->findAll();

        if (! empty($criteria)) {
            $results = $this->applyCriteria($results, $criteria);
        }

        if (! empty($orderBy)) {
            $results = $this->applyOrder($results, $orderBy);
        }

        return array_slice($results, $offset ?? 0, $limit);
    }

    /**
     * @param mixed[]      $criteria
     * @param mixed[]|null $orderBy
     *
     * @throws \InvalidArgumentException
     */
    public function findOneBy(array $criteria, array $orderBy = null): ?ResourceInterface
    {
        if (empty($criteria)) {
            throw new InvalidArgumentException('The criteria array needs to be set.');
        }

        $results = $this->applyCriteria($this->findAll(), $criteria);

        /** @var ResourceInterface|false $result */
        $result = reset($results);
        if (false !== $result) {
            return $result;
        }

        return null;
    }

    /**
     * @psalm-return class-string<mixed>
     */
    abstract public function getClassName(): string;

    /**
     * @param mixed[]  $criteria
     * @param string[] $sorting
     *
     * @return iterable<ResourceInterface>
     */
    public function createPaginator(array $criteria = [], array $sorting = []): iterable
    {
        $resources = $this->findAll();

        if (! empty($sorting)) {
            $resources = $this->applyOrder($resources, $sorting);
        }

        if (! empty($criteria)) {
            $resources = $this->applyCriteria($resources, $criteria);
        }

        return $this->getPaginator(...$resources);
    }

    /**
     * @param ResourceInterface[] $resources
     * @param mixed[] $criteria
     *
     * @return ResourceInterface[]
     */
    private function applyCriteria(array $resources, array $criteria): array
    {
        foreach ($this->arrayObject as $object) {
            foreach ($criteria as $criterion => $value) {
                $pValue = $this->accessor->getValue($object, $criterion);
                /** @noinspection TypeUnsafeArraySearchInspection */
                if (true === is_array($value) && true === in_array($pValue, $value)) {
                    continue;
                }

                if ($value === $pValue) {
                    continue;
                }

                /** @noinspection TypeUnsafeComparisonInspection */
                if (true === is_array($value) && true === is_array($pValue) && $value == $pValue) {
                    continue;
                }

                /** @noinspection TypeUnsafeComparisonInspection */
                if (true === is_object($value) && $value == $pValue) {
                    continue;
                }

                $key = array_search($object, $resources, true);
                unset($resources[$key]);
            }
        }

        return $resources;
    }

    /**
     * @param ResourceInterface[] $resources
     * @param mixed[] $orderBy
     *
     * @return ResourceInterface[]
     */
    private function applyOrder(array $resources, array $orderBy): array
    {
        $results = $resources;

        foreach ($orderBy as $property => $order) {
            $sortable = [];

            foreach ($results as $key => $object) {
                $sortable[$key] = $this->accessor->getValue($object, $property);
            }

            if (RepositoryInterface::ORDER_ASCENDING === $order) {
                asort($sortable);
            }
            if (RepositoryInterface::ORDER_DESCENDING === $order) {
                arsort($sortable);
            }

            $results = [];

            foreach ($sortable as $key => $propertyValue) {
                $results[$key] = $resources[$key];
            }
        }

        return $results;
    }

    /**
     * @psalm-template T of ResourceInterface
     * @psalm-param T[] $resources
     *
     * @return Pager<T>
     */
    protected function getPaginator(...$resources): Pager
    {
        return new Pager(new ArrayAdapter($resources));
    }
}
