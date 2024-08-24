<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\DomainEvent;

interface DeferredEventPublisherInterface
{
    public function disablePublishDeferredEvents(): void;
}
