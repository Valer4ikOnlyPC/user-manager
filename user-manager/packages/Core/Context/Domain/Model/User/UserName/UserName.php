<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Model\User\UserName;

class UserName
{
    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $secondName;

    /**
     * @var ?string
     */
    private $lastName;

    public function __construct(string $firstName, string $secondName, ?string $lastName)
    {
        $this->setFirstName($firstName);
        $this->setSecondName($secondName);
        $this->setLastName($lastName);
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    private function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function secondName(): string
    {
        return $this->secondName;
    }

    private function setSecondName(string $secondName): void
    {
        $this->secondName = $secondName;
    }

    public function lastName(): ?string
    {
        return $this->lastName;
    }

    private function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }
}
