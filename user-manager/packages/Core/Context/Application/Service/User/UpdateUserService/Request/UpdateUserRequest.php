<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\User\UpdateUserService\Request;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation as Serializer;
use UserManager\Core\Context\Application\Service\RequestInterface;
use UserManager\Core\Context\Application\Service\User\DTO\UserNameDTO;
use UserManager\Core\Context\Domain\Model\User\UserID;

class UpdateUserRequest implements RequestInterface
{
    /**
     * @var UserID
     * @Serializer\Type("string")
     * @Serializer\SerializedName("user_id")
     * @Accessor(setter="setUserID")
     */
    private $userID;

    /**
     * @var UserNameDTO
     * @Serializer\Type("UserManager\Core\Context\Application\Service\User\DTO\UserNameDTO")
     * @Serializer\SerializedName("name")
     */
    private $name;

    /**
     * @var boolean
     * @Serializer\Type("boolean")
     * @Serializer\SerializedName("is_admin")
     */
    private $isAdmin;

    public function __construct(string $userID, UserNameDTO $userNameDTO, bool $isAdmin)
    {
        $this->setUserID($userID);
        $this->setName($userNameDTO);
        $this->setIsAdmin($isAdmin);
    }

    public function userID(): UserID
    {
        return $this->userID;
    }

    public function setUserID(string $userID): void
    {
        $this->userID = new UserID($userID);
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
