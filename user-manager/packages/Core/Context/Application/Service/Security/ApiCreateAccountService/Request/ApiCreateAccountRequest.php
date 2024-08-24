<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\Security\ApiCreateAccountService\Request;

use JMS\Serializer\Annotation as Serializer;
use UserManager\Core\Context\Application\Service\RequestInterface;
use UserManager\Core\Context\Application\Service\User\DTO\UserNameDTO;

class ApiCreateAccountRequest implements RequestInterface
{
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

    /**
     * @var UserNameDTO
     * @Serializer\Type("UserManager\Core\Context\Application\Service\User\DTO\UserNameDTO")
     * @Serializer\SerializedName("name")
     */
    private $name;

    /**
     * @var boolean
     * @Serializer\Type("bool")
     * @Serializer\SerializedName("is_admin")
     */
    private $isAdmin;

    public function __construct(string $login, string $password, UserNameDTO $name, bool $isAdmin)
    {
        $this->setLogin($login);
        $this->setPassword($password);
        $this->setName($name);
        $this->setIsAdmin($isAdmin);
    }

    public function login(): string
    {
        return $this->login;
    }

    private function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function password(): string
    {
        return $this->password;
    }

    private function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function name(): UserNameDTO
    {
        return $this->name;
    }

    private function setName(UserNameDTO $name): void
    {
        $this->name = $name;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    private function setIsAdmin(bool $isAdmin): void
    {
        $this->isAdmin = $isAdmin;
    }
}
