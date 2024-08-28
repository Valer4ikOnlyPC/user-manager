<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Service\Photo\Remover;

use UserManager\Core\Context\Domain\Model\Photo\Photo;
use UserManager\Core\Context\Domain\Model\Photo\PhotoID;
use UserManager\Core\Context\Domain\Model\User\User;

interface PhotoRemoverInterface
{
    /**
     * @param Photo[] $photos
     */
    public function removeUserPhotos(User $user, array $photos): void;

    public function removeUserPhoto(User $user, PhotoID $photoID): void;
}
