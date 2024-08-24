<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Model\Security\Authentication;

use UserManager\Core\Common\Exception\AuthenticationException;
use UserManager\Core\Common\Exception\AuthenticationLogicException;
use UserManager\Core\Context\Domain\Model\Security\Authentication\AuthenticationRequest\AuthenticationRequestInterface;
use UserManager\Core\Context\Domain\Model\Security\UserInterface;

interface SecurityInterface
{
    /**
     * @throws AuthenticationException
     * @throws AuthenticationLogicException
     */
    public function authenticate(AuthenticationRequestInterface $request): void;

    public function user(): ?UserInterface;

    public function isAuthenticated(): bool;
}
