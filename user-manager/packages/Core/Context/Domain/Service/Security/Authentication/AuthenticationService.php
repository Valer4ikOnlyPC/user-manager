<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Service\Security\Authentication;

use UserManager\Core\Common\Exception\AuthenticationException;
use UserManager\Core\Common\Exception\LogicException;
use UserManager\Core\Context\Domain\Model\Security\Authentication\AuthenticationRequest\AuthenticationRequestInterface;
use UserManager\Core\Context\Domain\Model\Security\Authentication\AuthenticatorInterface;
use UserManager\Core\Context\Domain\Model\Security\Authentication\SecurityInterface;
use UserManager\Core\Context\Domain\Model\Security\UserInterface;

class AuthenticationService implements SecurityInterface
{
    /**
     * @var AuthenticatorInterface[]
     */
    private $authenticators = [];

    /**
     * @var null|UserInterface
     */
    private $user = null;

    /**
     * @var bool
     */
    private $isAuthenticated = false;

    /**
     * @param AuthenticatorInterface[] $authenticators
     */
    public function __construct(array $authenticators)
    {
        foreach ($authenticators as $authenticator) {
            $this->addAuthenticator($authenticator);
        }
    }

    public function authenticate(AuthenticationRequestInterface $request): void
    {
        $exceptions = [];

        foreach ($this->authenticators() as $authenticator) {
            if (false === $authenticator->supports($request)) {
                continue;
            }

            try {
                $response = $authenticator->authenticate($request);

                if (true === $response->isAuthenticated()) {
                    $this->user = $response->user();
                    $this->isAuthenticated = true;

                    return;
                }
            } catch (AuthenticationException $e) {
                $this->user = null;
                $this->isAuthenticated = false;

                $exceptions[] = $e;
            }
        }

        if (false === empty($exceptions)) {
            throw reset($exceptions);
        }

        throw new LogicException(
            sprintf('There is no authenticator for request class "%s"', get_class($request)),
            500
        );
    }

    public function user(): ?UserInterface
    {
        return $this->user;
    }

    public function isAuthenticated(): bool
    {
        return $this->isAuthenticated;
    }

    private function addAuthenticator(AuthenticatorInterface $authenticator): void
    {
        $this->authenticators[] = $authenticator;
    }

    /**
     * @return \Iterator|AuthenticatorInterface[]
     */
    private function authenticators(): \Iterator
    {
        foreach ($this->authenticators as $authenticator) {
            yield $authenticator;
        }
    }
}
