<?php

declare(strict_types=1);

namespace UserManager\Core\Common\Collection;

/**
 * @template TValue
 *
 * @extends \ArrayAccess<int|string, object>
 * @extends \IteratorAggregate<int|string, object>
 */
interface CollectionInterface extends \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * @param TValue $element
     */
    public function add($element): void;

    public function clear(): void;

    /**
     * @param TValue $element
     */
    public function contains($element): bool;

    public function isEmpty(): bool;

    /**
     * @param string|int $key
     */
    public function remove($key): void;

    /**
     * @param TValue $element
     */
    public function removeElement($element): bool;

    /**
     * @param string|int $key
     */
    public function containsKey($key): bool;

    /**
     * @param string|int $key
     *
     * @return TValue
     */
    public function get($key);

    /**
     * @psalm-return array<string|int>
     */
    public function getKeys(): array;

    /**
     * @return array<TValue>
     */
    public function getValues(): array;

    /**
     * @param string|int $key
     * @param TValue      $value
     */
    public function set($key, $value): void;

    /**
     * @return array<TValue>
     */
    public function toArray(): array;

    /**
     * @return mixed
     */
    public function first();

    /**
     * @return mixed
     */
    public function last();

    /**
     * @return int|string|null
     */
    public function key();

    /**
     * @return TValue
     * @psalm-return TValue|false
     */
    public function current();

    /**
     * @return TValue
     * @psalm-return TValue|false
     */
    public function next();

    public function exists(\Closure $p): bool;

    /**
     * @return CollectionInterface<TValue>
     */
    public function filter(\Closure $p): CollectionInterface;

    public function forAll(\Closure $p): bool;

    /**
     * @return CollectionInterface<TValue>
     */
    public function map(\Closure $func): CollectionInterface;

    /**
     * @return array<CollectionInterface<TValue>>
     */
    public function partition(\Closure $p): array;

    /**
     * @param mixed $element
     *
     * @return false|int|string
     */
    public function indexOf($element);

    /**
     * @return array<mixed>
     */
    public function slice(int $offset, ?int $length = null): array;

    /**
     * @return \Iterator<TValue>|\Traversable<TValue>
     */
    public function getIterator();

    /**
     * @param string|int $offset
     */
    public function offsetExists($offset): bool;

    /**
     * @param string|int $offset
     *
     * @return mixed
     */
    public function offsetGet($offset);

    /**
     * @param string|int $offset
     * @param mixed      $value
     */
    public function offsetSet($offset, $value): void;

    /**
     * @param string|int $offset
     */
    public function offsetUnset($offset): void;

    public function count(): int;
}
