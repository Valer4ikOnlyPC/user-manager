<?php

declare(strict_types=1);

namespace UserManager\Core\Common\Collection;

/**
 * @extends \IteratorAggregate<int|string, object>
 */
interface SortedCollectionInterface extends \Countable, \IteratorAggregate
{
    /**
     * @param mixed $element
     */
    public function add($element, int $position): void;

    /**
     * @param mixed $element
     */
    public function push($element): void;

    public function clear(): void;

    /**
     * @param mixed $element
     */
    public function contains($element): bool;

    public function isEmpty(): bool;

    /**
     * @param mixed $element
     */
    public function updateElementPosition($element, int $position): void;

    /**
     * @param mixed $element
     */
    public function removeElement($element): void;

    /**
     * @return array<mixed>
     */
    public function toArray(): array;
}
