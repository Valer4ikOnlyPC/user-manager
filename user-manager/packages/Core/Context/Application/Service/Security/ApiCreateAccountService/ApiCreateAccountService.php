<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\Security\ApiCreateAccountService;

use UserManager\Core\Common\Exception\AuthenticationException;
use UserManager\Core\Context\Application\Service\ApplicationService;
use UserManager\Core\Context\Application\Service\RequestInterface;
use UserManager\Core\Context\Application\Service\ResponseInterface;
use UserManager\Core\Context\Application\Service\Security\ApiCreateAccountService\Request\ApiCreateAccountRequest;
use UserManager\Core\Context\Application\Service\Security\ApiCreateAccountService\Response\ApiCreateAccountResponse;
use UserManager\Core\Context\Application\Service\User\DTO\UserDTO;
use UserManager\Core\Context\Domain\Model\Security\Authentication\Token\TokenBuilderInterface;
use UserManager\Core\Context\Domain\Model\User\User;
use UserManager\Core\Context\Domain\Model\User\UserID;
use UserManager\Core\Context\Domain\Model\User\UserName\UserName;
use UserManager\Core\Context\Domain\Model\User\UserRepositoryInterface;

/**
 * @method ApiCreateAccountResponse execute(ApiCreateAccountRequest $request);
 */
class ApiCreateAccountService extends ApplicationService
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var TokenBuilderInterface
     */
    private $tokenBuilder;

    public function __construct(UserRepositoryInterface $userRepository, TokenBuilderInterface $tokenBuilder)
    {
        $this->userRepository = $userRepository;
        $this->tokenBuilder = $tokenBuilder;
    }

    protected function supports(RequestInterface $request): bool
    {
        return $request instanceof ApiCreateAccountRequest;
    }

    /**
     * @param ApiCreateAccountRequest $request
     *
     * @return ApiCreateAccountResponse
     */
    protected function process(RequestInterface $request): ResponseInterface
    {
        $user = $this->userRepository->findOneBy([
            'login' => $request->login(),
        ]);
        if ($user !== null) {
            throw new AuthenticationException('User already exists.');
        }

        $user = new User(
            new UserID(),
            $request->login(),
            password_hash($request->password(), PASSWORD_BCRYPT),
            new UserName(
                $request->name()->firstName(),
                $request->name()->secondName(),
                $request->name()->lastName()
            )
        );
        $this->userRepository->add($user);

        return new ApiCreateAccountResponse($this->tokenBuilder->createToken($user), new UserDTO($user));
    }
}
