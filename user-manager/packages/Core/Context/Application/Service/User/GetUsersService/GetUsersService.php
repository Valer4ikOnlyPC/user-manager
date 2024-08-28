<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\User\GetUsersService;

use UserManager\Core\Context\Application\Service\ApplicationService;
use UserManager\Core\Context\Application\Service\RequestInterface;
use UserManager\Core\Context\Application\Service\ResponseInterface;
use UserManager\Core\Context\Application\Service\User\DTO\UserDTO;
use UserManager\Core\Context\Application\Service\User\GetUsersService\Request\GetUsersRequest;
use UserManager\Core\Context\Application\Service\User\GetUsersService\Response\GetUsersResponse;
use UserManager\Core\Context\Domain\Model\Security\Authentication\SecurityInterface;
use UserManager\Core\Context\Domain\Model\User\User;
use UserManager\Core\Context\Domain\Model\User\UserRepositoryInterface;

/**
 * @method GetUsersResponse execute(GetUsersRequest $request);
 */
class GetUsersService extends ApplicationService
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var SecurityInterface
     */
    private $security;

    public function __construct(UserRepositoryInterface $userRepository, SecurityInterface $security)
    {
        $this->userRepository = $userRepository;
        $this->security = $security;
    }

    protected function supports(RequestInterface $request): bool
    {
        return $request instanceof GetUsersRequest;
    }

    /**
     * @param GetUsersRequest $request
     *
     * @return GetUsersResponse
     */
    protected function process(RequestInterface $request): ResponseInterface
    {
        /** @var User|null $user */
        $user = $this->security->user();
        $users = [];
        $count = 0;
        if ($request->userID() !== null && $user !== null && $user->ID()->equals($request->userID())) {
            $users[] = new UserDTO($this->userRepository->find($user->ID()));
            $count = 1;
            return new GetUsersResponse($users, $count);
        }
        if ($user !== null && $user->isAdmin()) {
            $users = $this->userRepository->findByParameters($request->userName() !== null ? mb_convert_encoding($request->userName(), 'windows-1251', 'utf-8') : null)
                ->setMaxPerPage($request->perPage())
                ->setCurrentPage($request->page());
            $count = $users->count();
            $users = array_map(function (User $user) {
                return new UserDTO($user);
            }, iterator_to_array($users));
        } elseif ($user !== null && ! $user->isAdmin()) {
            $users[] = new UserDTO($this->userRepository->find($user->ID()));
            $count = 1;
        }

        return new GetUsersResponse($users, $count);
    }
}
