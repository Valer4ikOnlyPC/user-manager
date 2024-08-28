<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Service\Photo\Uploader;

use UserManager\Core\Context\Domain\Model\User\User;
use UserManager\Core\Context\Domain\Service\Photo\DTO\UploadPhotosDirectoryDTO;

interface PhotoUploaderInterface
{
    /**
     * @param string[] $images
     */
    public function uploadUserImages(array $images, ?User $user): UploadPhotosDirectoryDTO;

    public function addPhotosToUserAndTransfer(string $tmpDirID, User $user): void;
}
