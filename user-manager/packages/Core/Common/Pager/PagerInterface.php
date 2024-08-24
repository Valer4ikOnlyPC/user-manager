<?php

declare(strict_types=1);

namespace UserManager\Core\Common\Pager;

/**
 * @extends \IteratorAggregate<int|string, object>
 */
interface PagerInterface extends \Countable, \IteratorAggregate
{
    /**
     * @return PagerInterface<mixed>
     */
    public function setMaxPerPage(int $maxPerPage): PagerInterface;

    public function maxPerPage(): int;

    /**
     * @return PagerInterface<mixed>
     */
    public function setCurrentPage(int $currentPage): PagerInterface;

    public function currentPage(): int;

    public function nbPages(): int;

    /**
     * @return \Traversable<mixed>
     */
    public function getIterator(): \Traversable;
}
