<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\DomainEvent;

use UserManager\Core\Common\Exception\BadMethodCallException;

final class DomainEventPublisher
{
    /**
     * @var self|null
     */
    private static $instance = null;

    /**
     * @var array<DomainEventSubscriberInterface>
     */
    private static $initialSubscribers = [];

    /**
     * @var array<DomainEventSubscriberInterface>
     */
    private $subscribers = [];

    /**
     * @var array<DomainEventInterface>
     */
    private $deferredEvents = [];

    /**
     * @var array<DomainEventInterface>
     */
    private $transitedEvents = [];

    /**
     * @var bool
     */
    private static $isEnabled = true;

    private function __construct()
    {
    }

    /**
     * @throws BadMethodCallException
     * @deprecated Клонирование паблишера не поддерживается
     */
    public function __clone()
    {
        throw new BadMethodCallException('Клонирование паблишера не поддерживается.');
    }

    public static function instance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
            foreach (self::$initialSubscribers as $subscriber) {
                self::$instance->subscribe($subscriber);
            }
        }

        return self::$instance;
    }

    public function publish(DomainEventInterface $event): void
    {
        if (false === self::$isEnabled) {
            return;
        }

        if ($event instanceof DeferredDomainEventInterface) {
            $this->deferredEvents[] = $event;

            return;
        }

        if ($event instanceof TransitedDomainEvent) {
            $this->transitedEvents[] = $event;

            return;
        }

        $this->publishToSubscribers($event);
    }

    public function publishDeferredEvents(): void
    {
        foreach ($this->deferredEvents as $event) {
            $this->publishToSubscribers($event);
        }

        $this->deferredEvents = [];
    }

    public function publishTransitedEvents(): void
    {
        foreach ($this->transitedEvents as $event) {
            $this->publishToSubscribers($event);
        }

        $this->transitedEvents = [];
    }

    private function publishToSubscribers(DomainEventInterface $event): void
    {
        foreach ($this->subscribers as $subscriber) {
            if ($subscriber->isSubscribedTo($event)) {
                $subscriber->handle($event);
            }
        }
    }

    public function subscribe(DomainEventSubscriberInterface $subscriber): void
    {
        $this->subscribers[] = $subscriber;
    }

    /**
     * @param DomainEventSubscriberInterface[] $subscribers
     */
    public static function setInitialSubscribers(array $subscribers): self
    {
        foreach ($subscribers as $subscriber) {
            self::$initialSubscribers[] = $subscriber;
        }

        self::reset();

        return self::instance();
    }

    public static function reset(): void
    {
        self::$instance = null;
    }

    public static function close(): void
    {
        self::$instance = null;
        self::$initialSubscribers = [];
    }

    public function disable(): void
    {
        self::$isEnabled = false;
    }

    public function enable(): void
    {
        self::$isEnabled = true;
    }
}
