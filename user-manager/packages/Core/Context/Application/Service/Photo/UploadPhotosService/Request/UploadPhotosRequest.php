<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\Photo\UploadPhotosService\Request;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation as Serializer;
use UserManager\Core\Context\Application\Service\RequestInterface;
use UserManager\Core\Context\Domain\Model\User\UserID;

class UploadPhotosRequest implements RequestInterface
{
    /**
     * @var string[]
     * @Serializer\Type("array<string>")
     * @Serializer\SerializedName("photos")
     */
    private $photos;

    /**
     * @var ?UserID
     * @Serializer\Type("string")
     * @Serializer\SerializedName("user_id")
     * @Accessor(setter="setUserID")
     */
    private $userID = null;

    /**
     * @param string[] $photos
     */
    public function __construct(array $photos, ?string $userID)
    {
        $this->setPhotos(...$photos);
        $this->setUserID($userID);
    }

    /**
     * @return string[]
     */
    public function photos(): array
    {
        return $this->photos;
    }

    public function setPhotos(string ...$photos): void
    {
        $this->photos = $photos;
    }

    public function userID(): ?UserID
    {
        return $this->userID;
    }

    public function setUserID(?string $userID): void
    {
        $this->userID = new UserID($userID);
    }
}
