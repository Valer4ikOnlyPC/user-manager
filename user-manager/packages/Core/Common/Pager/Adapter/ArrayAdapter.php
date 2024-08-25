<?php

declare(strict_types=1);

namespace UserManager\Core\Common\Pager\Adapter;

use UserManager\Core\Common\Pager\PagerAdapterInterface;

class ArrayAdapter implements PagerAdapterInterface
{
    /**
     * @var array<mixed>
     */
    private $array;

    /**
     * @param array<mixed> $array
     */
    public function __construct(array $array)
    {
        $this->setArray($array);
    }

    /**
     * @param array<mixed> $array
     */
    private function setArray(array $array): void
    {
        $this->array = $array;
    }

    public function nbResults(): int
    {
        return \count($this->array);
    }

    public function slice(int $offset, int $length): iterable
    {
        return \array_slice($this->array, $offset, $length);
    }
}
