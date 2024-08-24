<?php

declare(strict_types=1);

namespace UserManager\Core\Common\Pager;

interface PagerAdapterInterface
{
    public function nbResults(): int;

    /**
     * @return iterable<mixed>
     */
    public function slice(int $offset, int $length): iterable;
}
