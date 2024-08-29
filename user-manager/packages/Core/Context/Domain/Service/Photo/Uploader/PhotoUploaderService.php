<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Service\Photo\Uploader;

use UserManager\Core\Context\Domain\Model\Photo\Photo;
use UserManager\Core\Context\Domain\Model\Photo\PhotoID;
use UserManager\Core\Context\Domain\Model\Photo\PhotoRepositoryInterface;
use UserManager\Core\Context\Domain\Model\User\User;
use UserManager\Core\Context\Domain\Model\User\UserID;
use UserManager\Core\Context\Domain\Service\EntityManagerAwareTrait;
use UserManager\Core\Context\Domain\Service\Photo\DTO\UploadPhotosDirectoryDTO;

class PhotoUploaderService implements PhotoUploaderInterface
{
    use EntityManagerAwareTrait;

    /**
     * @var string
     */
    private $photoPath;

    /**
     * @var PhotoRepositoryInterface
     */
    private $photoRepository;

    public function __construct(string $photoPath, PhotoRepositoryInterface $photoRepository)
    {
        $this->photoPath = $photoPath;
        $this->photoRepository = $photoRepository;
    }

    /**
     * @param string[] $images
     * @throws \Exception
     */
    public function uploadUserImages(array $images, ?User $user): UploadPhotosDirectoryDTO
    {
        $isTmp = $user === null;
        $userID = $isTmp ? new UserID() : $user->ID();
        $dir = $isTmp ? sprintf('tmp/%s', $userID) : $userID;
        if (! is_dir(sprintf('%s/%s', $this->photoPath, $dir))) {
            mkdir(sprintf('%s/%s', $this->photoPath, $dir), 0777, true);
        }

        $photos = [];
        foreach ($images as $imageContent) {
            if (! $isTmp && (count($images) + count($user->photos()) > 10)) {
                throw new \Exception("Maximum number of photos exceeded");
            }
            $fileExtension = (explode('/', (explode(';base64,', $imageContent))[0]))[1];
            if (! in_array($fileExtension, ['jpg', 'jpeg', 'png'], false)) {
                throw new \Exception("File extension '{$fileExtension}' is not allowed");
            }
            $fileID = new PhotoID();
            $output_file = sprintf('%s/%s/%s.%s', $this->photoPath, $dir, $fileID, $fileExtension);
            file_put_contents($output_file, file_get_contents($imageContent));

            $photos[] = new Photo(
                $fileID,
                $output_file,
                sprintf('/photos/%s/%s.%s', $dir, $fileID, $fileExtension)
            );
        }
        if (! $isTmp) {
            $this->addUserPhotos($user, $photos);
        }

        return new UploadPhotosDirectoryDTO($userID, $photos);
    }

    public function addPhotosToUserAndTransfer(string $tmpDirID, User $user): void
    {
        $tempDir = sprintf('%s/tmp/%s', $this->photoPath, $tmpDirID);
        $tempFiles = scandir($tempDir);
        $fileDir = sprintf('%s/%s', $this->photoPath, $user->ID());
        if (! is_dir($fileDir)) {
            mkdir($fileDir, 0777, true);
        }

        $photos = [];
        foreach ($tempFiles as $file) {
            if ($file !== '.' && $file !== '..') {
                $photos[] = new Photo(
                    new PhotoID((explode('.', $file))[0]),
                    sprintf('%s/%s', $fileDir, $file),
                    sprintf('/photos/%s/%s', (string) $user->ID(), $file)
                );
                rename(sprintf('%s/%s', $tempDir, $file), sprintf('%s/%s', $fileDir, $file));
            }
        }
        rmdir($tempDir);
        $this->addUserPhotos($user, $photos);
    }

    /**
     * @param Photo[] $photos
     */
    private function addUserPhotos(User $user, array $photos): void
    {
        if (count(array_merge($user->photos(), $photos)) > 10) {
            throw new \Exception("Maximum number of photos exceeded");
        }
        foreach ($photos as $photo) {
            $this->photoRepository->add($photo);
        }
        $this->em()->transactional(
            function () use ($user, $photos) {
                foreach ($photos as $photo) {
                    $user->addPhoto($photo);
                }
            }
        );
    }
}
