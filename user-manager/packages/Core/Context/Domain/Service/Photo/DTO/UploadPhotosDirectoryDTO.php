<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Service\Photo\DTO;

use JMS\Serializer\Annotation as Serializer;
use UserManager\Core\Context\Application\Service\Photo\DTO\PhotoDTO;
use UserManager\Core\Context\Domain\Model\Photo\Photo;
use UserManager\Core\Context\Domain\Model\User\UserID;

class UploadPhotosDirectoryDTO
{
    /**
     * @var string
     * @Serializer\SerializedName("user_id")
     */
    private $userID;

    /**
     * @var PhotoDTO[]
     * @Serializer\SerializedName("photos")
     */
    private $photos;

    /**
     * @param Photo[] $photos
     */
    public function __construct(UserID $userID, array $photos)
    {
        $this->setUserID($userID);
        $this->setPhotos(...$photos);
    }

    public function userID(): string
    {
        return $this->userID;
    }

    private function setUserID(UserID $userID): void
    {
        $this->userID = (string) $userID;
    }

    /**
     * @return PhotoDTO[]
     */
    public function photos(): array
    {
        return $this->photos;
    }

    private function setPhotos(Photo ...$photos): void
    {
        $this->photos = array_map(function ($photo) {
            return new PhotoDTO($photo);
        }, $photos);
    }
}
