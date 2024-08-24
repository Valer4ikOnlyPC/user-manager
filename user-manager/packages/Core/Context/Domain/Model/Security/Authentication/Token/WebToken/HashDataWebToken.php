<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Model\Security\Authentication\Token\WebToken;

use UserManager\Core\Context\Domain\Model\Security\Authentication\Token\HashDataInterface;

class HashDataWebToken implements HashDataInterface
{
    /**
     * @var string
     */
    private $login;

    /**
     * @var int
     */
    private $expires;

    /**
     * @var string
     */
    private $password;

    public function __construct(string $login, int $expires, string $password)
    {
        $this->setLogin($login);
        $this->setExpires($expires);
        $this->setPassword($password);
    }

    public function login(): string
    {
        return $this->login;
    }

    public function expires(): int
    {
        return $this->expires;
    }

    public function password(): string
    {
        return $this->password;
    }

    private function setLogin(string $login): void
    {
        $this->login = $login;
    }

    private function setExpires(int $expires): void
    {
        $this->expires = $expires;
    }

    private function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
