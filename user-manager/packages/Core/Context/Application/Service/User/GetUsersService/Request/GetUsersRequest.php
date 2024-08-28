<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\User\GetUsersService\Request;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation as Serializer;
use UserManager\Core\Context\Application\Service\RequestInterface;
use UserManager\Core\Context\Domain\Model\User\UserID;

class GetUsersRequest implements RequestInterface
{
    /**
     * @var ?string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("user_name")
     */
    private $userName;

    /**
     * @var ?UserID
     * @Serializer\Type("string")
     * @Serializer\SerializedName("user_id")
     * @Accessor(setter="setUserID")
     */
    private $userID = null;

    /**
     * @var int
     * @Serializer\Type("int")
     * @Serializer\SerializedName("page")
     */
    private $page = 1;

    /**
     * @var int
     * @Serializer\Type("int")
     * @Serializer\SerializedName("per_page")
     */
    private $perPage = 50;

    public function __construct(
        int $page,
        int $perPage,
        ?string $userName = null,
        ?string $userID = null
    ) {
        $this->setUserName($userName);
        $this->setPage($page);
        $this->setPerPage($perPage);
        $this->setUserID($userID);
    }

    public function userName(): ?string
    {
        return $this->userName;
    }

    private function setUserName(?string $userName): void
    {
        $this->userName = $userName;
    }

    public function perPage(): int
    {
        return $this->perPage;
    }

    private function setPerPage(int $perPage): void
    {
        $this->perPage = $perPage;
    }

    public function page(): int
    {
        return $this->page;
    }

    private function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function userID(): ?UserID
    {
        return $this->userID;
    }

    public function setUserID(?string $userID): void
    {
        $this->userID = $userID !== null ? new UserID($userID) : null;
    }
}
