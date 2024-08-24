<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\Security\ApiAuthenticationService\Request;

use JMS\Serializer\Annotation as Serializer;
use UserManager\Core\Context\Application\Service\RequestInterface;
use UserManager\Core\Context\Domain\Model\Security\Authentication\AuthenticationRequest\LoginAuthenticationRequestInterface;
use UserManager\Core\Context\Domain\Service\AuthenticationTokenTrait;

class ApiAuthenticationRequest implements RequestInterface, LoginAuthenticationRequestInterface
{
    use AuthenticationTokenTrait;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("login")
     */
    private $login;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("password")
     */
    private $password;

    public function __construct(string $login, string $password)
    {
        $this->setLogin($login);
        $this->setPassword($password);
    }

    public function login(): string
    {
        return $this->login;
    }

    public function password(): string
    {
        return $this->password;
    }

    private function setLogin(string $login): void
    {
        $this->login = $login;
    }

    private function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
