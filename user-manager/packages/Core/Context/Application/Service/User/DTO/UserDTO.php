<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\User\DTO;

use JMS\Serializer\Annotation as Serializer;
use UserManager\Core\Context\Application\Service\Photo\DTO\PhotoDTO;
use UserManager\Core\Context\Domain\Model\Photo\Photo;
use UserManager\Core\Context\Domain\Model\User\User;
use UserManager\Core\Context\Domain\Model\User\UserID;
use UserManager\Core\Context\Domain\Model\User\UserName\UserName;

class UserDTO
{
    /**
     * @var string
     * @Serializer\SerializedName("id")
     */
    private $ID;

    /**
     * @var string
     */
    private $login;

    /**
     * @var UserNameDTO
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

    /**
     * @var PhotoDTO[]
     */
    private $photos;

    public function __construct(User $user)
    {
        $this->setID($user->ID());
        $this->setLogin($user->login());
        $this->setName($user->name());
        $this->setUpdateDate($user->updateDate());
        $this->setIsAdmin($user->isAdmin());
        $this->setPhotos($user->photos());
    }

    public function ID(): string
    {
        return $this->ID;
    }

    private function setID(UserID $ID): void
    {
        $this->ID = (string) $ID;
    }

    public function login(): string
    {
        return $this->login;
    }

    private function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function name(): UserNameDTO
    {
        return $this->name;
    }

    private function setName(UserName $name): void
    {
        $this->name = new UserNameDTO($name);
    }

    public function updateDate(): \DateTimeImmutable
    {
        return $this->updateDate;
    }

    private function setUpdateDate(\DateTimeImmutable $updateDate): void
    {
        $this->updateDate = $updateDate;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    private function setIsAdmin(bool $isAdmin): void
    {
        $this->isAdmin = $isAdmin;
    }

    /**
     * @return PhotoDTO[]
     */
    public function photos(): array
    {
        return $this->photos;
    }

    /**
     * @param Photo[] $photos
     */
    public function setPhotos(array $photos): void
    {
        $this->photos = array_map(function (Photo $photo) {
            return new PhotoDTO($photo);
        }, $photos);
    }
}
