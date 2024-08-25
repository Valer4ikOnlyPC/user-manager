<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\User\UpdateUserService;

use UserManager\Core\Common\Exception\AuthenticationException;
use UserManager\Core\Context\Application\Service\ApplicationService;
use UserManager\Core\Context\Application\Service\RequestInterface;
use UserManager\Core\Context\Application\Service\ResponseInterface;
use UserManager\Core\Context\Application\Service\User\UpdateUserService\Request\UpdateUserRequest;
use UserManager\Core\Context\Application\Service\User\UpdateUserService\Response\UpdateUserResponse;
use UserManager\Core\Context\Domain\Model\Security\Authentication\SecurityInterface;
use UserManager\Core\Context\Domain\Model\User\User;
use UserManager\Core\Context\Domain\Model\User\UserName\UserName;
use UserManager\Core\Context\Domain\Model\User\UserRepositoryInterface;
use UserManager\Core\Context\Domain\Service\EntityManagerAwareTrait;

/**
 * @method UpdateUserResponse execute(UpdateUserRequest $request);
 */
class UpdateUserService extends ApplicationService
{
    use EntityManagerAwareTrait;

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
        return $request instanceof UpdateUserRequest;
    }

    /**
     * @param UpdateUserRequest $request
     *
     * @return UpdateUserResponse
     */
    protected function process(RequestInterface $request): ResponseInterface
    {
        /** @var User|null $thisUser */
        $thisUser = $this->security->user();
        if ($thisUser === null || (! $thisUser->isAdmin() && ! $thisUser->ID()->equals($request->userID()))) {
            throw new AuthenticationException('Access denied.');
        }

        $user = $this->userRepository->findOrFail($request->userID());
        $this->em()->transactional(
            function () use ($user, $thisUser, $request) {
                $user->updateName(
                    new UserName(
                        mb_convert_encoding($request->name()->firstName(), 'windows-1251', 'utf-8'),
                        mb_convert_encoding($request->name()->secondName(), 'windows-1251', 'utf-8'),
                        mb_convert_encoding($request->name()->lastName(), 'windows-1251', 'utf-8')
                    )
                );
                if ($thisUser->isAdmin() && ! $thisUser->ID()->equals($request->userID())) {
                    $user->updateIsAdmin($request->isAdmin());
                }
            }
        );

        return new UpdateUserResponse();
    }
}
