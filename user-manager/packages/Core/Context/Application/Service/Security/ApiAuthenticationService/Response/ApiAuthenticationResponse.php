<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\Security\ApiAuthenticationService\Response;

use UserManager\Core\Context\Application\Service\ResponseInterface;
use UserManager\Core\Context\Application\Service\User\DTO\UserDTO;

class ApiAuthenticationResponse implements ResponseInterface
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var UserDTO
     */
    private $user;

    public function __construct(string $token, UserDTO $user)
    {
        $this->setToken($token);
        $this->setUser($user);
    }

    public function token(): string
    {
        return $this->token;
    }

    private function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function user(): UserDTO
    {
        return $this->user;
    }

    private function setUser(UserDTO $user): void
    {
        $this->user = $user;
    }
}
