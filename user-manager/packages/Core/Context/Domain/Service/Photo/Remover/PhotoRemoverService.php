<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Service\Photo\Remover;

use UserManager\Core\Context\Domain\Model\Photo\Photo;
use UserManager\Core\Context\Domain\Model\Photo\PhotoID;
use UserManager\Core\Context\Domain\Model\Photo\PhotoRepositoryInterface;
use UserManager\Core\Context\Domain\Model\User\User;
use UserManager\Core\Context\Domain\Service\EntityManagerAwareTrait;

class PhotoRemoverService implements PhotoRemoverInterface
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
     * @param Photo[] $photos
     */
    public function removeUserPhotos(User $user, array $photos): void
    {
        $fileDir = sprintf('%s/%s', $this->photoPath, $user->ID());
        if (is_dir($fileDir)) {
            system('rm -rf -- ' . escapeshellarg($fileDir));
        }
        foreach ($photos as $photo) {
            $this->photoRepository->remove($photo);
        }
    }

    public function removeUserPhoto(User $user, PhotoID $photoID): void
    {
        $photo = $this->photoRepository->find($photoID);
        if (is_file($photo->dir())) {
            system('rm -rf -- ' . escapeshellarg($photo->dir()));
        }
        $user->removePhoto($photo);
        $this->photoRepository->remove($photo);
    }
}
