<?php

declare(strict_types=1);

namespace UserManager\tests\Core\Context\Domain\Model\User;

use PHPUnit\Framework\TestCase;
use UserManager\Core\Context\Domain\Model\User\User;
use UserManager\Core\Context\Domain\Model\User\UserID;
use UserManager\Core\Context\Domain\Model\User\UserName\UserName;

class UserTest extends TestCase
{
    /**
     * @dataProvider validCreationDataProvider
     */
    public function testItCanBeCreatedAndReturnsCorrectData(
        UserID $ID,
        string $login,
        string $password,
        UserName $name,
        bool $isAdmin
    ): void {
        $user = new User(
            $ID,
            $login,
            $password,
            $name,
            $isAdmin
        );

        self::assertEquals($ID, $user->ID());
        self::assertEquals($login, $user->login());
        self::assertEquals($password, $user->password());
        self::assertEquals($name, $user->name());
        self::assertEquals($isAdmin, $user->isAdmin());
    }

    public function validCreationDataProvider(): \Iterator
    {
        yield [
            new UserID(),
            'test-login-1',
            'test-password-1',
            new UserName(
                'Александр',
                'Александрович',
                'Александров'
            ),
            true,
        ];

        yield [
            new UserID(),
            'test-login-2',
            'test-password-2',
            new UserName(
                'Владимир',
                'Владимирович',
                'Владимиров'
            ),
            false,
        ];
    }

    /**
     * @dataProvider passwordDataProvider
     */
    public function testItCanPasswordEqual(
        string $password,
        bool $expectedResult
    ): void {
        $user = new User(
            new UserID(),
            'login',
            '$2y$16$N3oKwYdU7H0A5wdc2I0XLev.E1snYUj67qqBdFLY2NTJGvF3ZvwVu',
            new UserName(
                'firstName',
                'secondName',
                'lastName'
            ),
            true
        );

        self::assertEquals($expectedResult, $user->equalsPassword($password));
    }

    public function passwordDataProvider(): \Iterator
    {
        yield [
            'password',
            true,
        ];

        yield [
            'pass',
            false,
        ];
    }

    public function testItCanBeUpdated(): void
    {
        $userName = new UserName(
            'firstName1',
            'secondName1',
            'lastName1'
        );
        $user = new User(
            new UserID(),
            'login',
            'password',
            new UserName(
                'firstName',
                'secondName',
                'lastName'
            )
        );

        $user->updateName($userName);
        self::assertEquals($userName, $user->name());

        $user->updateIsAdmin(true);
        self::assertTrue($user->isAdmin());
    }
}
