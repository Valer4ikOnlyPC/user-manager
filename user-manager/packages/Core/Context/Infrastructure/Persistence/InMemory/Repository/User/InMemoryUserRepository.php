<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Infrastructure\Persistence\InMemory\Repository\User;

use UserManager\Core\Common\Pager\Pager;
use UserManager\Core\Context\Domain\Model\User\User;
use UserManager\Core\Context\Domain\Model\User\UserID;
use UserManager\Core\Context\Domain\Model\User\UserRepositoryInterface;
use UserManager\Core\Context\Infrastructure\Persistence\InMemory\Repository\InMemoryRepository;

/**
 * @method User|null find(UserID $id)
 * @method User      findOrFail(UserID $id)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 */
class InMemoryUserRepository extends InMemoryRepository implements UserRepositoryInterface
{
    public function getClassName(): string
    {
        return User::class;
    }

    public function findByParameters(?string $userName): Pager
    {
        $users = $this->findAll();
        if ($userName !== null) {
            $users = array_filter($users, function (User $user) use ($userName) {
                if ($user->name()->lastName()) {
                    return strpos($user->name()->firstName() . ' ' . $user->name()->secondName() . ' ' . $user->name()->lastName(), $userName) !== false;
                } else {
                    return strpos($user->name()->firstName() . ' ' . $user->name()->secondName(), $userName) !== false;
                }
            });
        }
        return $this->getPaginator(...$users);
    }
}
