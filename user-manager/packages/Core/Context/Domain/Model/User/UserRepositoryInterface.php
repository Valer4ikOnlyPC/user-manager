<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Model\User;

use UserManager\Core\Common\Pager\Pager;
use UserManager\Core\Context\Domain\Model\RepositoryInterface;

/**
 * @method User|null find(UserID $id)
 * @method User      findOrFail(UserID $id)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 */
interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Pager<User>
     */
    public function findByParameters(?string $userName): Pager;
}
