<?php

declare(strict_types=1);

namespace UserManager\Core\Common\Pager;

use UserManager\Core\Common\Exception\InvalidArgumentException;

class Pager implements PagerInterface
{
    /**
     * @var PagerAdapterInterface
     */
    protected $adapter;

    /**
     * @var int
     */
    protected $maxPerPage;

    /**
     * @var int
     */
    protected $currentPage = 1;

    public function __construct(PagerAdapterInterface $adapter)
    {
        $this->setAdapter($adapter);

        $countElements = $adapter->nbResults();
        $this->setMaxPerPage(1 > $countElements ? 1 : $countElements);
    }

    protected function setAdapter(PagerAdapterInterface $adapter): void
    {
        $this->adapter = $adapter;
    }

    protected function adapter(): PagerAdapterInterface
    {
        return $this->adapter;
    }

    public function count(): int
    {
        return $this->adapter()->nbResults();
    }

    public function setMaxPerPage(int $maxPerPage): PagerInterface
    {
        if (1 > $maxPerPage) {
            throw new InvalidArgumentException('Количество позиций на страницу не может быть меньше 1.');
        }

        $this->maxPerPage = $maxPerPage;

        if ($this->currentPage() > $this->nbPages()) {
            $this->setCurrentPage($this->nbPages());
        }

        return $this;
    }

    public function maxPerPage(): int
    {
        return $this->maxPerPage;
    }

    public function nbPages(): int
    {
        $results = $this->adapter()->nbResults();

        return (int) ceil((1 > $results ? 1 : $results) / $this->maxPerPage());
    }

    public function setCurrentPage(int $currentPage): PagerInterface
    {
        if ($this->nbPages() < $currentPage) {
            throw new InvalidArgumentException('Номер текущей страницы не может превышать реальное количество страниц.');
        }

        $this->currentPage = $currentPage;

        return $this;
    }

    public function currentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @return \Traversable<mixed>
     * @throws \Exception
     */
    public function getIterator(): \Traversable
    {
        $results = $this->adapter()->slice($this->calculateOffsetForCurrentPageResults(), $this->maxPerPage());

        if ($results instanceof \Iterator) {
            return $results;
        }

        if ($results instanceof \IteratorAggregate) {
            return $results->getIterator();
        }

        /** @var array<mixed> $results */
        return new \ArrayIterator($results);
    }

    protected function calculateOffsetForCurrentPageResults(): int
    {
        return ($this->currentPage() - 1) * $this->maxPerPage();
    }
}
