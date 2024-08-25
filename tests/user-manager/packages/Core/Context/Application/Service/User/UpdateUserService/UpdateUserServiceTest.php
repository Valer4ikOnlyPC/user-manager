<?php

declare(strict_types=1);

namespace UserManager\tests\Core\Context\Application\Service\User\UpdateUserService;

use PHPUnit\Framework\TestCase;
use UserManager\Core\Common\Exception\AuthenticationException;
use UserManager\Core\Context\Application\Service\User\DTO\UserNameDTO;
use UserManager\Core\Context\Application\Service\User\UpdateUserService\Request\UpdateUserRequest;
use UserManager\Core\Context\Application\Service\User\UpdateUserService\Response\UpdateUserResponse;
use UserManager\Core\Context\Application\Service\User\UpdateUserService\UpdateUserService;
use UserManager\Core\Context\Domain\Model\Security\Authentication\SecurityInterface;
use UserManager\Core\Context\Domain\Model\User\User;
use UserManager\Core\Context\Domain\Model\User\UserID;
use UserManager\Core\Context\Domain\Model\User\UserName\UserName;
use UserManager\Core\Context\Domain\Model\User\UserRepositoryInterface;
use UserManager\Core\Context\Domain\Service\EntityManagerInterface;
use UserManager\Core\Context\Infrastructure\Persistence\InMemory\Repository\User\InMemoryUserRepository;

class UpdateUserServiceTest extends TestCase
{
    public function testItCanBeCreated(): void
    {
        $service = new UpdateUserService(
            self::createMock(UserRepositoryInterface::class),
            self::createMock(SecurityInterface::class)
        );

        $this->assertInstanceOf(UpdateUserService::class, $service);
    }

    /**
     * @dataProvider validServiceDataProvider
     */
    public function testItCanUpdateUser(
        UserRepositoryInterface $userRepository,
        SecurityInterface $security,
        UpdateUserRequest $request
    ): void {
        $service = new UpdateUserService(
            $userRepository,
            $security
        );
        $em = $this->createMock(EntityManagerInterface::class);
        $em->method('transactional')
            ->willReturnCallback(function (callable $func) {
                call_user_func($func);
            });
        $service->setEntityManager($em);

        $response = $service->execute($request);
        $user = $userRepository->findOrFail($request->userID());
        $this->assertInstanceOf(UpdateUserResponse::class, $response);
        $this->assertEquals($request->isAdmin(), $user->isAdmin());
        $this->assertEquals($request->name()->firstName(), $user->name()->firstName());
        $this->assertEquals($request->name()->secondName(), $user->name()->secondName());
        $this->assertEquals($request->name()->lastName(), $user->name()->lastName());
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
        $userRepository->add(new User(
            $user->ID(),
            'login',
            'pass',
            new UserName(
                'firstName',
                'secondName',
                'lastName'
            ),
            true
        ));

        yield [
            $userRepository,
            $securityInterface,
            new UpdateUserRequest(
                (string) $user->ID(),
                new UserNameDTO(
                    new UserName(
                        'new_firstName',
                        'new_secondName',
                        'new_lastName'
                    )
                ),
                true
            ),
        ];

        $user = new User(
            new UserID(),
            'login',
            'pass',
            new UserName(
                'firstName',
                'secondName',
                'lastName'
            ),
            true
        );
        $userRepository->add($user);
        yield [
            $userRepository,
            $securityInterface,
            new UpdateUserRequest(
                (string) $user->ID(),
                new UserNameDTO(
                    new UserName(
                        'new_firstName',
                        'new_secondName',
                        'new_lastName'
                    )
                ),
                false
            ),
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testItThrowsErrorWhenAccessDenied(
        UserRepositoryInterface $userRepository,
        SecurityInterface $security,
        UpdateUserRequest $request,
        string $exceptionClass,
        string $expectedMessage
    ): void {
        $service = new UpdateUserService(
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
            new UpdateUserRequest(
                (string) $user1->ID(),
                self::createMock(UserNameDTO::class),
                false
            ),
            AuthenticationException::class,
            'Access denied.',
        ];

        yield [
            $userRepository,
            $securityInterface,
            new UpdateUserRequest(
                (string) $user1->ID(),
                self::createMock(UserNameDTO::class),
                false
            ),
            AuthenticationException::class,
            'Access denied.',
        ];
    }
}
