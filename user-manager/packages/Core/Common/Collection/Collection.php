<?php

declare(strict_types=1);

namespace UserManager\Core\Common\Collection;

use Doctrine\Common\Collections\ArrayCollection as DoctrineArrayCollection;
use Doctrine\Common\Collections\Collection as DoctrineCollectionInterface;
use Doctrine\ORM\PersistentCollection as DoctrinePersistentCollection;

/**
 * @implements CollectionInterface<object>
 */
class Collection implements CollectionInterface
{
    /**
     * @var DoctrineCollectionInterface<object>|DoctrineArrayCollection<int|string, object>|DoctrinePersistentCollection<object>
     * @psalm-var DoctrineArrayCollection<int|string, object>|DoctrinePersistentCollection<object>
     */
    protected $elements;

    /**
     * @param array<object> $elements
     */
    public function __construct(array $elements = [])
    {
        $this->setElements(new DoctrineArrayCollection($elements));
    }

    /**
     * @param DoctrineArrayCollection<int|string, object> $elements
     */
    private function setElements(DoctrineArrayCollection $elements): void
    {
        $this->elements = $elements;
    }

    public function add($element): void
    {
        $this->elements->add($element);
    }

    public function clear(): void
    {
        $this->elements->clear();
    }

    public function contains($element): bool
    {
        return $this->elements->contains($element);
    }

    public function isEmpty(): bool
    {
        return $this->elements->isEmpty();
    }

    public function remove($key): void
    {
        $this->elements->remove($key);
    }

    public function removeElement($element): bool
    {
        return $this->elements->removeElement($element);
    }

    public function containsKey($key): bool
    {
        return $this->elements->containsKey($key);
    }

    public function get($key)
    {
        return $this->elements->get($key);
    }

    public function getKeys(): array
    {
        return $this->elements->getKeys();
    }

    public function getValues(): array
    {
        return $this->elements->getValues();
    }

    public function set($key, $value): void
    {
        $this->elements->set($key, $value);
    }

    public function toArray(): array
    {
        return $this->elements->toArray();
    }

    public function first()
    {
        return $this->elements->first();
    }

    public function last()
    {
        return $this->elements->last();
    }

    public function key()
    {
        return $this->elements->key();
    }

    public function current()
    {
        return $this->elements->current();
    }

    public function next()
    {
        return $this->elements->next();
    }

    public function exists(\Closure $p): bool
    {
        return $this->elements->exists($p);
    }

    public function filter(\Closure $p): CollectionInterface
    {
        return new self($this->elements->filter($p)->toArray());
    }

    public function forAll(\Closure $p): bool
    {
        return $this->elements->forAll($p);
    }

    public function map(\Closure $func): CollectionInterface
    {
        return new self($this->elements->map($func)->toArray());
    }

    public function partition(\Closure $p): array
    {
        $partitions = $this->elements->partition($p);

        return [
            new self($partitions[0]->toArray()),
            new self($partitions[1]->toArray()),
        ];
    }

    public function indexOf($element)
    {
        return $this->elements->indexOf($element);
    }

    public function slice(int $offset, ?int $length = null): array
    {
        return $this->elements->slice($offset, $length);
    }

    /**
     * @throws \Exception
     */
    public function getIterator()
    {
        return $this->elements->getIterator();
    }

    public function offsetExists($offset): bool
    {
        return $this->elements->offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        return $this->elements->offsetGet($offset);
    }

    public function offsetSet($offset, $value): void
    {
        $this->elements->offsetSet($offset, $value);
    }

    public function offsetUnset($offset): void
    {
        $this->elements->offsetUnset($offset);
    }

    public function count(): int
    {
        return $this->elements->count();
    }
}
