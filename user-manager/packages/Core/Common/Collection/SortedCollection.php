<?php

declare(strict_types=1);

namespace UserManager\Core\Common\Collection;

use Doctrine\Common\Collections\ArrayCollection as DoctrineArrayCollection;
use Doctrine\Common\Collections\Collection as DoctrineCollectionInterface;
use Doctrine\ORM\PersistentCollection as DoctrinePersistentCollection;

class SortedCollection implements SortedCollectionInterface
{
    /**
     * @var DoctrineCollectionInterface<SortedCollectionItem>|DoctrineArrayCollection<int|string, SortedCollectionItem>|DoctrinePersistentCollection<SortedCollectionItem>
     * @psalm-var DoctrineArrayCollection<int|string, SortedCollectionItem>|DoctrinePersistentCollection<SortedCollectionItem>
     */
    protected $items;

    /**
     * @param array<int|string, SortedCollectionItem> $elements
     */
    public function __construct(array $elements)
    {
        $this->setItems($elements);
    }

    /**
     * @psalm-return class-string<SortedCollectionItem>
     */
    protected static function itemClass(): string
    {
        return SortedCollectionItem::class;
    }

    /**
     * @param array<int|string, object> $elements
     */
    protected function setItems(array $elements): void
    {
        $itemClass = static::itemClass();

        $sortedElements = $this->sortElements($elements);

        /** @var SortedCollectionItem[] $items */
        $items = [];

        foreach ($sortedElements as $position => $element) {
            $items[] = new $itemClass($element, $position);
        }

        $this->items = new DoctrineArrayCollection($items);
    }

    /**
     * @param array<object> $elements
     *
     * @return array<object>
     */
    protected function sortElements(array $elements): array
    {
        uksort($elements, function ($keyA, $keyB) {
            return (int) $keyA - (int) $keyB;
        });

        return array_values($elements);
    }

    /**
     * @return array<SortedCollectionItem>
     */
    protected function sortedItems(): array
    {
        $items = $this->items->toArray();
        uasort($items, function (SortedCollectionItem $itemA, SortedCollectionItem $itemB) {
            return $itemA->position() - $itemB->position();
        });

        return array_values($items);
    }

    protected function addItem(SortedCollectionItem $item): void
    {
        if (true === $this->items->contains($item)) {
            return;
        }

        if (0 === $this->items->count()) {
            $item->updatePosition(0);
            $this->items->add($item);

            return;
        }

        if (0 > $item->position()) {
            $item->updatePosition(0);
        } elseif ($this->items->count() < $item->position()) {
            $item->updatePosition($this->items->count());
        }

        /** @var SortedCollectionItem $existingItem */
        foreach ($this->items as $existingItem) {
            if ($existingItem->position() >= $item->position()) {
                $existingItem->updatePosition($existingItem->position() + 1);
            }
        }

        $this->items->add($item);
    }

    protected function sortItems(): void
    {
        $items = $this->sortedItems();

        /**
         * @var int                  $position
         * @var SortedCollectionItem $item
         */
        foreach ($items as $position => $item) {
            $item->updatePosition($position);
        }
    }

    public function add($element, int $position): void
    {
        $itemClass = static::itemClass();
        $this->addItem(new $itemClass($element, $position));
    }

    public function push($element): void
    {
        $this->add($element, $this->count());
    }

    public function clear(): void
    {
        $this->items->clear();
    }

    public function contains($element): bool
    {
        return $this->items->exists(function ($key, SortedCollectionItem $item) use ($element) {
            return $item->element() === $element;
        });
    }

    public function isEmpty(): bool
    {
        return $this->items->isEmpty();
    }

    public function updateElementPosition($element, int $position): void
    {
        $items = $this->items->filter(function (SortedCollectionItem $item) use ($element) {
            return $item->element() === $element;
        });

        /** @var SortedCollectionItem $item */
        foreach ($items as $item) {
            $this->items->removeElement($item);
            $this->sortItems();
            $item->updatePosition($position);
            $this->addItem($item);
        }
    }

    public function removeElement($element): void
    {
        $itemsToRemove = $this->items->filter(function (SortedCollectionItem $item) use ($element) {
            return $item->element() === $element;
        });

        foreach ($itemsToRemove as $itemToRemove) {
            $this->items->removeElement($itemToRemove);
        }

        $this->sortItems();
    }

    /**
     * @return \Iterator<mixed>|\Traversable<mixed>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }

    public function count(): int
    {
        return $this->items->count();
    }

    public function toArray(): array
    {
        return array_map(function (SortedCollectionItem $item) {
            return $item->element();
        }, $this->sortedItems());
    }
}
