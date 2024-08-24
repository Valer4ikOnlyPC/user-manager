<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Exception;

class MultipleResourcesByIdNotFoundException extends \RuntimeException
{
    public function __construct(string ...$ids)
    {
        parent::__construct(sprintf('Ресурсы с ID "%s" не найдены.', implode('", "', $ids)), 404);
    }
}
