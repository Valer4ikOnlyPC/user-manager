<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\DomainEvent;

use DateTimeImmutable;

abstract class DomainEvent implements DomainEventInterface
{
    /**
     * @var DateTimeImmutable
     */
    private $occurredOn;

    public function __construct()
    {
        $this->occurredOn = new DateTimeImmutable();
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
