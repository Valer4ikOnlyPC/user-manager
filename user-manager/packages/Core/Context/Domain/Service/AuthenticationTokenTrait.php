<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Service;

trait AuthenticationTokenTrait
{
    /**
     * @var string|null
     */
    protected $authenticationToken = null;

    public function authenticationToken(): ?string
    {
        return $this->authenticationToken;
    }

    public function setAuthenticationToken(?string $authenticationToken): void
    {
        $this->authenticationToken = $authenticationToken;
    }
}
