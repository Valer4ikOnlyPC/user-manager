<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Infrastructure\Persistence\Doctrine\Repository\User;

use UserManager\Core\Common\Pager\Pager;
use UserManager\Core\Context\Domain\Model\User\User;
use UserManager\Core\Context\Domain\Model\User\UserID;
use UserManager\Core\Context\Domain\Model\User\UserRepositoryInterface;
use UserManager\Core\Context\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

/**
 * @method User|null find(UserID $id)
 * @method User      findOrFail(UserID $id)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 */
class DoctrineUserRepository extends DoctrineRepository implements UserRepositoryInterface
{
    public function getClassName(): string
    {
        return User::class;
    }

    public function findByParameters(?string $userName): Pager
    {
        $query = $this->createQueryBuilder('u');
        if ($userName !== null) {
            $query
                ->andWhere("u.name.lastName is not null and CONCAT(u.name.firstName, ' ', u.name.secondName, ' ', u.name.lastName) LIKE :userName")
                ->orWhere("u.name.lastName is null and CONCAT(u.name.firstName, ' ', u.name.secondName) LIKE :userName")
                ->setParameter('userName', "%{$userName}%");
        }
        return $this->getPaginator(
            $query
        );
    }
}
