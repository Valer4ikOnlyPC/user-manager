<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Model\Security\Authentication;

use UserManager\Core\Context\Domain\Model\Security\UserInterface;

class AuthenticationResponse
{
    /**
     * @var null|UserInterface
     */
    private $user;

    /**
     * @var bool
     */
    private $isAuthenticated;

    public function __construct(?UserInterface $user, bool $isAuthenticated)
    {
        $this->setUser($user);
        $this->setIsAuthenticated($isAuthenticated);
    }

    public function user(): ?UserInterface
    {
        return $this->user;
    }

    public function isAuthenticated(): bool
    {
        return $this->isAuthenticated;
    }

    private function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    private function setIsAuthenticated(bool $isAuthenticated): void
    {
        $this->isAuthenticated = $isAuthenticated;
    }
}
