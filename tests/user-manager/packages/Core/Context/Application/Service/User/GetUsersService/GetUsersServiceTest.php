<?php

declare(strict_types=1);

namespace UserManager\tests\Core\Context\Application\Service\User\GetUsersService;

use PHPUnit\Framework\TestCase;
use UserManager\Core\Context\Application\Service\User\DTO\UserDTO;
use UserManager\Core\Context\Application\Service\User\GetUsersService\GetUsersService;
use UserManager\Core\Context\Application\Service\User\GetUsersService\Request\GetUsersRequest;
use UserManager\Core\Context\Application\Service\User\GetUsersService\Response\GetUsersResponse;
use UserManager\Core\Context\Domain\Model\Security\Authentication\SecurityInterface;
use UserManager\Core\Context\Domain\Model\User\User;
use UserManager\Core\Context\Domain\Model\User\UserID;
use UserManager\Core\Context\Domain\Model\User\UserName\UserName;
use UserManager\Core\Context\Domain\Model\User\UserRepositoryInterface;
use UserManager\Core\Context\Infrastructure\Persistence\InMemory\Repository\User\InMemoryUserRepository;

class GetUsersServiceTest extends TestCase
{
    public function testItCanBeCreated(): void
    {
        $service = new GetUsersService(
            self::createMock(UserRepositoryInterface::class),
            self::createMock(SecurityInterface::class)
        );

        $this->assertInstanceOf(GetUsersService::class, $service);
    }

    /**
     * @dataProvider validServiceDataProvider
     */
    public function testItCanGetUsersList(
        UserRepositoryInterface $userRepository,
        SecurityInterface $security,
        GetUsersRequest $request,
        GetUsersResponse $expectedResponse
    ): void {
        $service = new GetUsersService(
            $userRepository,
            $security
        );

        $response = $service->execute($request);
        $this->assertInstanceOf(GetUsersResponse::class, $response);
        $this->assertEquals($expectedResponse, $response);
    }

    public function validServiceDataProvider(): \Iterator
    {
        $user = $this->createConfiguredMock(User::class, [
            'ID' => new UserID(),
            'isAdmin' => true,
        ]);

        $user1 = $this->createConfiguredMock(User::class, [
            'ID' => new UserID(),
            'isAdmin' => false,
            'login' => 'login',
            'password' => 'password',
            'name' => new UserName('firstName', 'secondName', 'lastName'),
        ]);

        $securityInterface = self::createMock(SecurityInterface::class);
        $securityInterface
            ->expects(self::any())
            ->method('user')
            ->willReturn($user, $user, $user1, null);

        $userRepository = new InMemoryUserRepository();
        $resultUsers = [];
        foreach ($this->getUsers(10) as $userGenerated) {
            $userRepository->add($userGenerated);
            $resultUsers[] = new UserDTO($userGenerated);
        }

        yield [
            $userRepository,
            $securityInterface,
            new GetUsersRequest(
                1,
                50
            ),
            new GetUsersResponse(
                $resultUsers,
                count($resultUsers)
            ),
        ];

        yield [
            $userRepository,
            $securityInterface,
            new GetUsersRequest(
                1,
                50,
                '1'
            ),
            new GetUsersResponse(
                [$resultUsers[1]],
                1
            ),
        ];

        $userRepository = new InMemoryUserRepository();
        $userRepository->add($user1);
        yield [
            $userRepository,
            $securityInterface,
            new GetUsersRequest(
                1,
                50
            ),
            new GetUsersResponse(
                [new UserDTO($user1)],
                1
            ),
        ];

        yield [
            $userRepository,
            $securityInterface,
            new GetUsersRequest(
                1,
                50
            ),
            new GetUsersResponse(
                [],
                0
            ),
        ];
    }

    private function getUsers(int $count): \Generator
    {
        for ($i = 0; $i < $count; $i++) {
            yield new User(
                new UserID(),
                'login' . $i,
                'pass' . $i,
                new UserName(
                    'firstName' . $i,
                    'secondName' . $i,
                    'lastName' . $i
                )
            );
        }
    }
}
