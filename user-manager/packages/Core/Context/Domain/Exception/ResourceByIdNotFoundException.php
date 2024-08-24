<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Exception;

class ResourceByIdNotFoundException extends \RuntimeException
{
    public function __construct(string $id)
    {
        parent::__construct(sprintf('Ресурс с ID "%s" не найден.', $id), 404);
    }
}
