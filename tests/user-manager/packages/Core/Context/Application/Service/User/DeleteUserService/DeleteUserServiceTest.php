<?php

declare(strict_types=1);

namespace UserManager\tests\Core\Context\Application\Service\User\DeleteUserService;

use PHPUnit\Framework\TestCase;
use UserManager\Core\Common\Exception\AuthenticationException;
use UserManager\Core\Context\Application\Service\User\DeleteUserService\DeleteUserService;
use UserManager\Core\Context\Application\Service\User\DeleteUserService\Request\DeleteUserRequest;
use UserManager\Core\Context\Application\Service\User\DeleteUserService\Response\DeleteUserResponse;
use UserManager\Core\Context\Domain\Model\Security\Authentication\SecurityInterface;
use UserManager\Core\Context\Domain\Model\User\User;
use UserManager\Core\Context\Domain\Model\User\UserID;
use UserManager\Core\Context\Domain\Model\User\UserRepositoryInterface;
use UserManager\Core\Context\Infrastructure\Persistence\InMemory\Repository\User\InMemoryUserRepository;

class DeleteUserServiceTest extends TestCase
{
    public function testItCanBeCreated(): void
    {
        $service = new DeleteUserService(
            self::createMock(UserRepositoryInterface::class),
            self::createMock(SecurityInterface::class)
        );

        $this->assertInstanceOf(DeleteUserService::class, $service);
    }

    /**
     * @dataProvider validServiceDataProvider
     */
    public function testItCanDeleteUser(
        UserRepositoryInterface $userRepository,
        SecurityInterface $security,
        DeleteUserRequest $request
    ): void {
        $service = new DeleteUserService(
            $userRepository,
            $security
        );

        $response = $service->execute($request);
        $this->assertInstanceOf(DeleteUserResponse::class, $response);
        $this->assertNull($userRepository->find($request->userID()));
    }

    public function validServiceDataProvider(): \Iterator
    {
        $user = $this->createConfiguredMock(User::class, [
            'ID' => new UserID(),
            'isAdmin' => true,
        ]);

        $securityInterface = self::createMock(SecurityInterface::class);
        $securityInterface
            ->expects(self::any())
            ->method('user')
            ->willReturn($user);
        $userRepository = new InMemoryUserRepository();
        $userRepository->add($user);

        yield [
            $userRepository,
            $securityInterface,
            new DeleteUserRequest(
                (string) $user->ID()
            ),
        ];

        $user1 = $this->createConfiguredMock(User::class, [
            'ID' => new UserID(),
            'isAdmin' => true,
        ]);
        $userRepository->add($user1);

        yield [
            $userRepository,
            $securityInterface,
            new DeleteUserRequest(
                (string) $user1->ID()
            ),
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testItThrowsErrorWhenAccessDenied(
        UserRepositoryInterface $userRepository,
        SecurityInterface $security,
        DeleteUserRequest $request,
        string $exceptionClass,
        string $expectedMessage
    ): void {
        $service = new DeleteUserService(
            $userRepository,
            $security
        );

        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($expectedMessage);
        $service->execute($request);
    }

    public function invalidDataProvider(): \Iterator
    {
        $user = $this->createConfiguredMock(User::class, [
            'ID' => new UserID(),
            'isAdmin' => false,
        ]);

        $securityInterface = self::createMock(SecurityInterface::class);
        $securityInterface
            ->expects(self::any())
            ->method('user')
            ->willReturn($user, null);
        $userRepository = new InMemoryUserRepository();
        $user1 = $this->createConfiguredMock(User::class, [
            'ID' => new UserID(),
            'isAdmin' => true,
        ]);
        $userRepository->add($user1);

        yield [
            $userRepository,
            $securityInterface,
            new DeleteUserRequest(
                (string) $user1->ID()
            ),
            AuthenticationException::class,
            'Access denied.',
        ];

        yield [
            $userRepository,
            $securityInterface,
            new DeleteUserRequest(
                (string) $user1->ID()
            ),
            AuthenticationException::class,
            'Access denied.',
        ];
    }
}
