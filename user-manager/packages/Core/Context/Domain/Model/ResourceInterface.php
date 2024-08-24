<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Model;

interface ResourceInterface
{
    /**
     * @return mixed
     */
    public function ID();
}
