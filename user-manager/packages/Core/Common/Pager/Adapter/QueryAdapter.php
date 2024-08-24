<?php

declare(strict_types=1);

namespace UserManager\Core\Common\Pager\Adapter;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use UserManager\Core\Common\Pager\PagerAdapterInterface;

class QueryAdapter implements PagerAdapterInterface
{
    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @param Query|QueryBuilder $query
     */
    public function __construct($query, bool $fetchJoinCollection = true, ?bool $useOutputWalkers = null)
    {
        $this->paginator = new Paginator($query, $fetchJoinCollection);
        $this->paginator->setUseOutputWalkers($useOutputWalkers);
    }

    public function nbResults(): int
    {
        return \count($this->paginator);
    }

    /**
     * @return iterable<mixed>
     * @throws \Exception
     */
    public function slice(int $offset, int $length): iterable
    {
        $this->paginator->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults($length);

        return $this->paginator->getIterator();
    }
}
