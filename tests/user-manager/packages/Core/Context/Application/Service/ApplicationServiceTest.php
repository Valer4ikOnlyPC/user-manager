<?php

declare(strict_types=1);

namespace UserManager\tests\Core\Context\Application\Service;

use PHPUnit\Framework\TestCase;
use UserManager\Core\Context\Application\Exception\UnsupportedRequestException;
use UserManager\Core\Context\Application\Service\ApplicationService;
use UserManager\Core\Context\Application\Service\RequestInterface;
use UserManager\Core\Context\Application\Service\ResponseInterface;
use UserManager\Core\Context\Domain\DomainEvent\DeferredDomainEventInterface;
use UserManager\Core\Context\Domain\DomainEvent\DomainEventInterface;
use UserManager\Core\Context\Domain\DomainEvent\DomainEventPublisher;
use UserManager\Core\Context\Domain\DomainEvent\DomainEventSubscriberInterface;
use UserManager\Core\Context\Domain\DomainEvent\TransitedDomainEventInterface;

class ApplicationServiceTest extends TestCase
{
    public function testItHasExecuteMethodThatThrowsExceptionForNotSupportedRequest(): void
    {
        $this->expectException(UnsupportedRequestException::class);

        $service = $this->createConfiguredMock(ApplicationService::class, [
            'supports' => false,
        ]);

        $service->execute($this->createStub(RequestInterface::class));
    }

    public function testItHasExecuteMethodThatReturnsResponseForSupportedRequest(): void
    {
        $response = $this->createStub(ResponseInterface::class);

        $service = $this->getMockForAbstractClass(ApplicationService::class);
        $service->method('supports')->willReturn(true);
        $service->method('process')->willReturn($response);

        $request = $this->createStub(RequestInterface::class);

        self::assertInstanceOf(ResponseInterface::class, $service->execute($request));
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function testItPublishDeferredAndTransitedEvents(): void
    {
        DomainEventPublisher::setInitialSubscribers(
            [
                $this->makeDummySubscriberOnce(DeferredDomainEventInterface::class),
                $this->makeDummySubscriberOnce(TransitedDomainEventInterface::class),
            ]
        );

        $response = $this->createStub(ResponseInterface::class);

        $service = $this->getMockForAbstractClass(ApplicationService::class);
        $service->method('supports')->willReturn(true);

        $deferredEvent = $this->createStub(DeferredDomainEventInterface::class);
        $transitedEvent = $this->createStub(TransitedDomainEventInterface::class);

        $service->method('process')->willReturnCallback(
            function (RequestInterface $request) use ($response, $deferredEvent, $transitedEvent): ResponseInterface {
                DomainEventPublisher::instance()->publish($deferredEvent);
                DomainEventPublisher::instance()->publish($transitedEvent);
                return $response;
            }
        );

        $request = $this->createStub(RequestInterface::class);

        $service->execute($request);
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

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function testItNotPublishDeferredEventsIsDisabled(): void
    {
        DomainEventPublisher::setInitialSubscribers(
            [
                $this->makeDummySubscriberNever(DeferredDomainEventInterface::class),
            ]
        );

        $response = $this->createStub(ResponseInterface::class);

        $service = $this->getMockForAbstractClass(ApplicationService::class);
        $service->method('supports')->willReturn(true);

        $deferredEvent = $this->createStub(DeferredDomainEventInterface::class);

        $service->method('process')->willReturnCallback(
            function (RequestInterface $request) use ($response, $deferredEvent): ResponseInterface {
                DomainEventPublisher::instance()->publish($deferredEvent);
                return $response;
            }
        );

        $request = $this->createStub(RequestInterface::class);

        $service->disablePublishDeferredEvents();
        $service->execute($request);
    }

    /**
     * @noinspection PhpSameParameterValueInspection
     */
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
