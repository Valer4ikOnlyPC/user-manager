<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\Security\ApiAuthenticationService\Response;

use UserManager\Core\Context\Application\Service\ResponseInterface;

class ApiAuthenticationResponse implements ResponseInterface
{
    /**
     * @var string
     */
    private $token;

    public function __construct(string $token)
    {
        $this->setToken($token);
    }

    public function token(): string
    {
        return $this->token;
    }

    private function setToken(string $token): void
    {
        $this->token = $token;
    }
}
