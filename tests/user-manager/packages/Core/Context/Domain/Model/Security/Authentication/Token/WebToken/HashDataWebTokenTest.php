<?php

declare(strict_types=1);

namespace UserManager\tests\Core\Context\Domain\Model\Security\Authentication\Token\WebToken;

use PHPUnit\Framework\TestCase;
use UserManager\Core\Context\Domain\Model\Security\Authentication\Token\WebToken\HashDataWebToken;

class HashDataWebTokenTest extends TestCase
{
    /**
     * @dataProvider validCreationDataProvider
     */
    public function testItCanBeCreated(
        string $login,
        int $expires,
        string $password
    ): void {
        $hashDataWebToken = new HashDataWebToken(
            $login,
            $expires,
            $password
        );

        self::assertEquals($login, $hashDataWebToken->login());
        self::assertEquals($expires, $hashDataWebToken->expires());
        self::assertEquals($password, $hashDataWebToken->password());
    }

    public function validCreationDataProvider(): \Iterator
    {
        yield [
            'login',
            time() + 60 * 60 * 24,
            'password',
        ];
    }
}
