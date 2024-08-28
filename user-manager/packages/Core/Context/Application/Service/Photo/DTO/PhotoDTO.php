<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\Photo\DTO;

use JMS\Serializer\Annotation as Serializer;
use UserManager\Core\Context\Domain\Model\Photo\Photo;
use UserManager\Core\Context\Domain\Model\Photo\PhotoID;

class PhotoDTO
{
    /**
     * @var string
     * @Serializer\SerializedName("id")
     */
    private $ID;

    /**
     * @var string
     */
    private $webDir;

    public function __construct(Photo $photo)
    {
        $this->setID($photo->ID());
        $this->setWebDir($photo->webDir());
    }

    public function ID(): string
    {
        return $this->ID;
    }

    private function setID(PhotoID $ID): void
    {
        $this->ID = (string) $ID;
    }

    public function webDir(): string
    {
        return $this->webDir;
    }

    private function setWebDir(string $webDir): void
    {
        $this->webDir = $webDir;
    }
}
