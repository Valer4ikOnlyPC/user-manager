<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Model\Photo;

use UserManager\Core\Context\Domain\Model\RepositoryInterface;

/**
 * @method Photo|null find(PhotoID $id)
 * @method Photo      findOrFail(PhotoID $id)
 * @method Photo[]    findAll()
 * @method Photo[]    findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method Photo|null findOneBy(array $criteria, array $orderBy = null)
 */
interface PhotoRepositoryInterface extends RepositoryInterface
{
}
