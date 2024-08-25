<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\User\GetUsersService\Request;

use JMS\Serializer\Annotation as Serializer;
use UserManager\Core\Context\Application\Service\RequestInterface;

class GetUsersRequest implements RequestInterface
{
    /**
     * @var ?string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("user_name")
     */
    private $userName;

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
        ?string $userName = null
    ) {
        $this->setUserName($userName);
        $this->setPage($page);
        $this->setPerPage($perPage);
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
}
