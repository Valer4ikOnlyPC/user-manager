<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\User\DeleteUserService;

use ArrayObject;
use UserManager\Core\Common\Exception\AuthenticationException;
use UserManager\Core\Context\Application\Service\ApplicationService;
use UserManager\Core\Context\Application\Service\RequestInterface;
use UserManager\Core\Context\Application\Service\ResponseInterface;
use UserManager\Core\Context\Application\Service\User\DeleteUserService\Request\DeleteUserRequest;
use UserManager\Core\Context\Application\Service\User\DeleteUserService\Response\DeleteUserResponse;
use UserManager\Core\Context\Domain\Model\Security\Authentication\SecurityInterface;
use UserManager\Core\Context\Domain\Model\User\User;
use UserManager\Core\Context\Domain\Model\User\UserRepositoryInterface;
use UserManager\Core\Context\Domain\Service\EntityManagerAwareTrait;
use UserManager\Core\Context\Domain\Service\Photo\Remover\PhotoRemoverInterface;

/**
 * @method DeleteUserResponse execute(DeleteUserRequest $request);
 */
class DeleteUserService extends ApplicationService
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

    /**
     * @var PhotoRemoverInterface
     */
    private $photoRemover;

    public function __construct(UserRepositoryInterface $userRepository, SecurityInterface $security, PhotoRemoverInterface $photoRemover)
    {
        $this->userRepository = $userRepository;
        $this->security = $security;
        $this->photoRemover = $photoRemover;
    }

    protected function supports(RequestInterface $request): bool
    {
        return $request instanceof DeleteUserRequest;
    }

    /**
     * @param DeleteUserRequest $request
     *
     * @return DeleteUserResponse
     */
    protected function process(RequestInterface $request): ResponseInterface
    {
        /** @var User|null $user */
        $user = $this->security->user();
        if ($user === null || (! $user->isAdmin() && ! $user->ID()->equals($request->userID()))) {
            throw new AuthenticationException('Access denied.');
        }

        $user = $this->userRepository->findOrFail($request->userID());
        $photos = (new ArrayObject($user->photos()))->getArrayCopy();
        $this->em()->transactional(
            function () use ($user) {
                foreach ($user->photos() as $photo) {
                    $user->removePhoto($photo);
                }
            }
        );
        $this->photoRemover->removeUserPhotos($user, $photos);
        $this->userRepository->remove($user);

        return new DeleteUserResponse();
    }
}
