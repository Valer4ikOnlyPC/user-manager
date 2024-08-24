<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service;

use UserManager\Apps\Main\ServiceLocator;
use UserManager\Core\Context\Application\Exception\UnsupportedRequestException;
use UserManager\Core\Context\Domain\DomainEvent\DeferredEventPublisherInterface;
use UserManager\Core\Context\Domain\DomainEvent\DomainEventPublisher;
use UserManager\Core\Context\Domain\Model\Security\Authentication\AuthenticationRequest\AuthenticationRequestInterface;
use UserManager\Core\Context\Domain\Service\Security\Authentication\AuthenticationService;

abstract class ApplicationService implements ApplicationServiceInterface, DeferredEventPublisherInterface
{
    /**
     * @var bool
     */
    protected $needPublishDeferredEvents = true;

    abstract protected function supports(RequestInterface $request): bool;

    /**
     * @throws UnsupportedRequestException
     */
    final public function execute(RequestInterface $request): ResponseInterface
    {
        DomainEventPublisher::reset();

        if (false === $this->supports($request)) {
            throw new UnsupportedRequestException(sprintf('Unsupported request %s.', get_class($request)));
        }

        if ($request instanceof AuthenticationRequestInterface) {
            /** @var AuthenticationService $authService */
            /** @phpstan-ignore-next-line */
            $authService = ServiceLocator::getService('user_manager.domain.security.authentication');
            $authService->authenticate($request);
        }

        $response = $this->process($request);

        DomainEventPublisher::instance()->publishTransitedEvents();

        if ($this->needPublishDeferredEvents) {
            DomainEventPublisher::instance()->publishDeferredEvents();
        }

        return $response;
    }

    abstract protected function process(RequestInterface $request): ResponseInterface;

    public function disablePublishDeferredEvents(): void
    {
        $this->needPublishDeferredEvents = false;
    }
}
