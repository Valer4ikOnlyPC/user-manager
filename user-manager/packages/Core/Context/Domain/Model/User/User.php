<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Model\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UserManager\Core\Context\Domain\Model\Photo\Photo;
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

    /**
     * @var Collection<int|string, Photo>
     */
    private $photos;

    /**
     * @param Photo[] $photos
     */
    public function __construct(
        UserID $ID,
        string $login,
        string $password,
        UserName $name,
        bool $isAdmin = false,
        array $photos = []
    ) {
        $this->setID($ID);
        $this->setLogin($login);
        $this->setPassword($password);
        $this->setIsAdmin($isAdmin);
        $this->setName($name);
        $this->setPhotos(...$photos);
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

    /**
     * @return Photo[]
     */
    public function photos(): array
    {
        return $this->photos->toArray();
    }

    private function setPhotos(Photo ...$photos): void
    {
        $this->photos = new ArrayCollection();
        array_map([$this, 'addPhoto'], $photos);
    }

    public function addPhoto(Photo $photo): void
    {
        if (false === $this->hasPhoto($photo)) {
            $this->photos->add($photo);
            $this->setUpdateDate();
        }
    }

    public function updatePhotos(Photo ...$photos): void
    {
        foreach ($this->photos as $photo) {
            if (false === in_array($photo, $photos, true)) {
                $this->removePhoto($photo);
            }
        }
        array_map([$this, 'addPhoto'], $photos);
        $this->setUpdateDate();
    }

    public function removePhoto(Photo $photo): void
    {
        if (true === $this->hasPhoto($photo)) {
            $this->photos->removeElement($photo);
            $this->setUpdateDate();
        }
    }

    public function hasPhoto(Photo $photo): bool
    {
        return $this->photos->contains($photo);
    }
}
