<?php

declare(strict_types=1);

namespace UserManager\tests\Core\Context\Domain\DomainEvent;

use PHPUnit\Framework\TestCase;
use UserManager\Core\Common\Exception\BadMethodCallException;
use UserManager\Core\Context\Domain\DomainEvent\DeferredDomainEventInterface;
use UserManager\Core\Context\Domain\DomainEvent\DomainEventInterface;
use UserManager\Core\Context\Domain\DomainEvent\DomainEventPublisher;
use UserManager\Core\Context\Domain\DomainEvent\DomainEventSubscriberInterface;
use UserManager\Core\Context\Domain\DomainEvent\TransitedDomainEvent;

class DomainEventPublisherTest extends TestCase
{
    public function testItShouldBeSameInstance(): void
    {
        $firstPublisher = DomainEventPublisher::instance();
        $secondPublisher = DomainEventPublisher::instance();

        self::assertSame($firstPublisher, $secondPublisher);
    }

    public function testItThrowsAnExceptionWhenTryingToClone(): void
    {
        $this->expectException(BadMethodCallException::class);

        (clone DomainEventPublisher::instance());
    }

    public function testItIsDeliveringThePublishedEventToItsSubscriber(): void
    {
        DomainEventPublisher::instance()->subscribe($this->makeDummySubscriberOnce(DomainEventInterface::class));

        DomainEventPublisher::instance()->publish($this->createStub(DomainEventInterface::class));
    }

    public function testItIsDeliveringThePublishedEventToItsInitialSubscriber(): void
    {
        DomainEventPublisher::reset();
        DomainEventPublisher::setInitialSubscribers(
            [
                $this->makeDummySubscriberOnce(DomainEventInterface::class),
                $this->makeDummySubscriberOnce(DomainEventInterface::class),
            ]
        );

        DomainEventPublisher::instance()->publish($this->createStub(DomainEventInterface::class));
    }

    public function testItIsNotDeliveringThePublishedDeferredEventToItsSubscriber(): void
    {
        DomainEventPublisher::setInitialSubscribers(
            [
                $this->makeDummySubscriberNever(DeferredDomainEventInterface::class),
                $this->makeDummySubscriberNever(DomainEventInterface::class),
            ]
        );

        DomainEventPublisher::instance()->subscribe(
            $this->makeDummySubscriberNever(DeferredDomainEventInterface::class)
        );

        DomainEventPublisher::instance()->publish(
            $this->createStub(DeferredDomainEventInterface::class)
        );
    }

    public function testItIsDeliveringThePublishedDeferredEventToItsSubscriber(): void
    {
        DomainEventPublisher::setInitialSubscribers(
            [
                $this->makeDummySubscriberOnce(DeferredDomainEventInterface::class),
                $this->makeDummySubscriberNever(TransitedDomainEvent::class),
            ]
        );

        DomainEventPublisher::instance()->subscribe(
            $this->makeDummySubscriberOnce(DeferredDomainEventInterface::class)
        );

        DomainEventPublisher::instance()->publish(
            $this->createStub(DeferredDomainEventInterface::class)
        );

        DomainEventPublisher::instance()->publishDeferredEvents();
    }

    public function testItIsNotDeliveringThePublishedTransitedEventToItsSubscriber(): void
    {
        DomainEventPublisher::setInitialSubscribers(
            [
                $this->makeDummySubscriberNever(TransitedDomainEvent::class),
                $this->makeDummySubscriberNever(DomainEventInterface::class),
            ]
        );

        DomainEventPublisher::instance()->subscribe(
            $this->makeDummySubscriberNever(TransitedDomainEvent::class)
        );

        DomainEventPublisher::instance()->publish(
            $this->createStub(TransitedDomainEvent::class)
        );
    }

    public function testItIsDeliveringThePublishedTransitedEventToItsSubscriber(): void
    {
        DomainEventPublisher::setInitialSubscribers(
            [
                $this->makeDummySubscriberNever(DeferredDomainEventInterface::class),
                $this->makeDummySubscriberOnce(TransitedDomainEvent::class),
            ]
        );

        DomainEventPublisher::instance()->subscribe(
            $this->makeDummySubscriberOnce(TransitedDomainEvent::class)
        );

        DomainEventPublisher::instance()->publish(
            $this->createStub(TransitedDomainEvent::class)
        );

        DomainEventPublisher::instance()->publishTransitedEvents();
    }

    private function makeDummySubscriberOnce(string $eventClassname): DomainEventSubscriberInterface
    {
        $subscriber = $this->createMock(DomainEventSubscriberInterface::class);

        $subscriberCallback = function (DomainEventInterface $event) use ($eventClassname): bool {
            return $event instanceof $eventClassname;
        };

        $subscriber
            ->method('isSubscribedTo')
            ->willReturnCallback($subscriberCallback);

        $subscriber
            ->expects(self::once())
            ->method('handle')
            ->with(self::callback($subscriberCallback));

        return $subscriber;
    }

    private function makeDummySubscriberNever(string $eventClassname): DomainEventSubscriberInterface
    {
        $subscriber = $this->createMock(DomainEventSubscriberInterface::class);

        $subscriberCallback = function (DomainEventInterface $event) use ($eventClassname): bool {
            return $event instanceof $eventClassname;
        };

        $subscriber
            ->method('isSubscribedTo')
            ->willReturnCallback($subscriberCallback);

        $subscriber
            ->expects(self::never())
            ->method('handle')
            ->with(self::callback($subscriberCallback));

        return $subscriber;
    }
}
