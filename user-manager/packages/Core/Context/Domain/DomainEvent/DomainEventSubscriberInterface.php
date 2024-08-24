<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\DomainEvent;

interface DomainEventSubscriberInterface
{
    public function isSubscribedTo(DomainEventInterface $event): bool;

    public function handle(DomainEventInterface $event): void;
}
