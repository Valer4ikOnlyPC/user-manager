<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Model;

interface NullableVOInterface
{
    public function isNull(): bool;
}
