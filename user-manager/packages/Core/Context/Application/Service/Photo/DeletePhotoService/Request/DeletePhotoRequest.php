<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\Photo\DeletePhotoService\Request;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation as Serializer;
use UserManager\Core\Context\Application\Service\RequestInterface;
use UserManager\Core\Context\Domain\Model\Photo\PhotoID;
use UserManager\Core\Context\Domain\Model\User\UserID;

class DeletePhotoRequest implements RequestInterface
{
    /**
     * @var UserID
     * @Serializer\Type("string")
     * @Serializer\SerializedName("user_id")
     * @Accessor(setter="setUserID")
     */
    private $userID;

    /**
     * @var PhotoID
     * @Serializer\Type("string")
     * @Serializer\SerializedName("photo_id")
     * @Accessor(setter="setPhotoID")
     */
    private $photoID;

    public function __construct(string $userID, string $photoID)
    {
        $this->setUserID($userID);
        $this->setPhotoID($photoID);
    }

    public function userID(): UserID
    {
        return $this->userID;
    }

    public function setUserID(string $userID): void
    {
        $this->userID = new UserID($userID);
    }

    public function photoID(): PhotoID
    {
        return $this->photoID;
    }

    public function setPhotoID(string $photoID): void
    {
        $this->photoID = new PhotoID($photoID);
    }
}
