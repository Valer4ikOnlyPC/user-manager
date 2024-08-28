<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Infrastructure\Persistence\Doctrine\Type\Photo;

use UserManager\Core\Common\Model\UUID;
use UserManager\Core\Context\Domain\Model\Photo\PhotoID;
use UserManager\Core\Context\Infrastructure\Persistence\Doctrine\Type\DoctrineUuidBinaryType;

class DoctrinePhotoID extends DoctrineUuidBinaryType
{
    public const NAME = 'PhotoID';

    protected function convertToConcreteClass(UUID $uuid)
    {
        return new PhotoID($uuid->__toString());
    }
}
