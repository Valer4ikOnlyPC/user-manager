<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Infrastructure\Persistence\Doctrine\Type\User;

use UserManager\Core\Common\Model\UUID;
use UserManager\Core\Context\Domain\Model\User\UserID;
use UserManager\Core\Context\Infrastructure\Persistence\Doctrine\Type\DoctrineUuidBinaryType;

class DoctrineUserID extends DoctrineUuidBinaryType
{
    public const NAME = 'UserID';

    protected function convertToConcreteClass(UUID $uuid)
    {
        return new UserID($uuid->__toString());
    }
}
