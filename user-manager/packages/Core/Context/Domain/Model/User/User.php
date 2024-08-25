<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Model\User;

use UserManager\Core\Context\Domain\Model\ResourceInterface;
use UserManager\Core\Context\Domain\Model\Security\UserInterface;
use UserManager\Core\Context\Domain\Model\User\UserName\UserName;

class User implements ResourceInterface, UserInterface
{
    /**
     * @var UserID
     */
    private $ID;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    /**
     * @var UserName
     */
    private $name;

    /**
     * @var \DateTimeImmutable
     */
    private $updateDate;

    /**
     * @var boolean
     */
    private $isAdmin;

    public function __construct(
        UserID $ID,
        string $login,
        string $password,
        UserName $name,
        bool $isAdmin = false
    ) {
        $this->setID($ID);
        $this->setLogin($login);
        $this->setPassword($password);
        $this->setIsAdmin($isAdmin);
        $this->setName($name);
        $this->setUpdateDate();
    }

    public function ID(): UserID
    {
        return $this->ID;
    }

    private function setID(UserID $ID): void
    {
        $this->ID = $ID;
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

    public function equalsPassword(string $password): bool
    {
        return password_verify($password, $this->password());
    }

    public function name(): UserName
    {
        return $this->name;
    }

    public function updateName(UserName $name): void
    {
        $this->setName($name);
        $this->setUpdateDate();
    }

    private function setName(UserName $name): void
    {
        $this->name = $name;
    }

    public function updateDate(): \DateTimeImmutable
    {
        return $this->updateDate;
    }

    public function setUpdateDate(): void
    {
        $this->updateDate = new \DateTimeImmutable();
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    private function setIsAdmin(bool $isAdmin): void
    {
        $this->isAdmin = $isAdmin;
    }

    public function updateIsAdmin(bool $isAdmin): void
    {
        $this->setIsAdmin($isAdmin);
        $this->setUpdateDate();
    }
}
