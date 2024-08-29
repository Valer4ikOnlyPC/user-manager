<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\Photo\DeletePhotoService;

use UserManager\Core\Common\Exception\AuthenticationException;
use UserManager\Core\Context\Application\Service\ApplicationService;
use UserManager\Core\Context\Application\Service\Photo\DeletePhotoService\Request\DeletePhotoRequest;
use UserManager\Core\Context\Application\Service\Photo\DeletePhotoService\Response\DeletePhotoResponse;
use UserManager\Core\Context\Application\Service\RequestInterface;
use UserManager\Core\Context\Application\Service\ResponseInterface;
use UserManager\Core\Context\Domain\Model\Security\Authentication\SecurityInterface;
use UserManager\Core\Context\Domain\Model\User\User;
use UserManager\Core\Context\Domain\Model\User\UserRepositoryInterface;
use UserManager\Core\Context\Domain\Service\Photo\Remover\PhotoRemoverInterface;

/**
 * @method DeletePhotoResponse execute(DeletePhotoRequest $request);
 */
class DeletePhotoService extends ApplicationService
{
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
        return $request instanceof DeletePhotoRequest;
    }

    /**
     * @param DeletePhotoRequest $request
     *
     * @return DeletePhotoResponse
     */
    protected function process(RequestInterface $request): ResponseInterface
    {
        /** @var User|null $user */
        $user = $this->security->user();
        if ($user === null || (! $user->isAdmin() && ! $user->ID()->equals($request->userID()))) {
            throw new AuthenticationException('Access denied.');
        }

        $user = $this->userRepository->findOrFail($request->userID());
        $this->photoRemover->removeUserPhoto($user, $request->photoID());

        return new DeletePhotoResponse();
    }
}
