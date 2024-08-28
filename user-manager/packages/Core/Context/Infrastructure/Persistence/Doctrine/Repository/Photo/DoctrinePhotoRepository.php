<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Infrastructure\Persistence\Doctrine\Repository\Photo;

use UserManager\Core\Context\Domain\Model\Photo\Photo;
use UserManager\Core\Context\Domain\Model\Photo\PhotoID;
use UserManager\Core\Context\Domain\Model\Photo\PhotoRepositoryInterface;
use UserManager\Core\Context\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

/**
 * @method Photo|null find(PhotoID $id)
 * @method Photo      findOrFail(PhotoID $id)
 * @method Photo[]    findAll()
 * @method Photo[]    findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method Photo|null findOneBy(array $criteria, array $orderBy = null)
 */
class DoctrinePhotoRepository extends DoctrineRepository implements PhotoRepositoryInterface
{
    public function getClassName(): string
    {
        return Photo::class;
    }
}
