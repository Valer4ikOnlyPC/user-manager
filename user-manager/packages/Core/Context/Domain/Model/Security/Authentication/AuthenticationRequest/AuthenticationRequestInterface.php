<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Model\Security\Authentication\AuthenticationRequest;

interface AuthenticationRequestInterface
{
    public function authenticationToken(): ?string;

    public function setAuthenticationToken(?string $authenticationToken): void;
}
