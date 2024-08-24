<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Model\Security\Authentication;

use UserManager\Core\Common\Exception\AuthenticationException;
use UserManager\Core\Context\Domain\Model\Security\Authentication\AuthenticationRequest\AuthenticationRequestInterface;

interface AuthenticatorInterface
{
    public function supports(AuthenticationRequestInterface $request): bool;

    /**
     * @throws AuthenticationException
     */
    public function authenticate(AuthenticationRequestInterface $request): AuthenticationResponse;
}
