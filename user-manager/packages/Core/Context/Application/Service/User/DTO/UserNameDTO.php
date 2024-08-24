<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\User\DTO;

use JMS\Serializer\Annotation as Serializer;
use UserManager\Core\Context\Domain\Model\User\UserName\UserName;

class UserNameDTO
{
    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("first_name")
     */
    private $firstName;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("second_name")
     */
    private $secondName;

    /**
     * @var ?string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("last_name")
     */
    private $lastName;

    public function __construct(UserName $userName)
    {
        $this->setFirstName($userName->firstName());
        $this->setSecondName($userName->secondName());
        $this->setLastName($userName->lastName());
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
