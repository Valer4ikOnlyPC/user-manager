<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Service;

interface EntityManagerInterface
{
    public function flush(): void;

    /**
     * @return mixed
     */
    public function transactional(callable $func);
}
