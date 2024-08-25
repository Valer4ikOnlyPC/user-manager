<?php

declare(strict_types=1);

namespace UserManager\tests\Core\Context\Domain\Model\Security\Authentication\Token\WebToken;

use PHPUnit\Framework\TestCase;
use UserManager\Core\Context\Domain\Model\Security\Authentication\Token\WebToken\HashDataWebToken;
use UserManager\Core\Context\Domain\Model\Security\Authentication\Token\WebToken\HashWebTokenGenerator;

class HashWebTokenGeneratorTest extends TestCase
{
    /**
     * @dataProvider validHashDataProvider
     */
    public function testGenerateHash(
        string $login,
        int $expires,
        string $password,
        string $secret,
        string $expectedHash
    ): void {
        $hashGenerator = new HashWebTokenGenerator($secret);
        $hash = $hashGenerator->generateHash(
            new HashDataWebToken($login, $expires, $password)
        );

        self::assertEquals($expectedHash, $hash);
    }

    public function validHashDataProvider(): \Iterator
    {
        $login = 'login';
        $expires = time() + 60 * 60 * 24;
        $password = 'password';
        $secret = 'secret';

        $expectedHash = hash_hmac(
            'sha256',
            $login . ':' . $expires . ':' . $password,
            $secret
        );

        yield [
            $login,
            $expires,
            $password,
            $secret,
            $expectedHash,
        ];
    }
}
