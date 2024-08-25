<?php

declare(strict_types=1);

namespace UserManager\tests\Core\Context\Domain\Model\Security\Authentication\Token\WebToken;

use PHPUnit\Framework\TestCase;
use UserManager\Core\Context\Domain\Model\Security\Authentication\Token\HashTokenGeneratorInterface;
use UserManager\Core\Context\Domain\Model\Security\Authentication\Token\TokenEncoderInterface;
use UserManager\Core\Context\Domain\Model\Security\Authentication\Token\WebToken\HashDataWebToken;
use UserManager\Core\Context\Domain\Model\Security\Authentication\Token\WebToken\HashWebTokenGenerator;
use UserManager\Core\Context\Domain\Model\Security\Authentication\Token\WebToken\WebTokenBuilder;
use UserManager\Core\Context\Domain\Model\Security\UserInterface;
use UserManager\Core\Context\Domain\Model\User\User;
use UserManager\Core\Context\Domain\Model\User\UserID;

class WebTokenBuilderTest extends TestCase
{
    /**
     * @dataProvider validTokenDataProvider
     */
    public function testCreateToken(
        UserInterface $user,
        HashTokenGeneratorInterface $hashTokenGenerator,
        TokenEncoderInterface $tokenEncoder,
        string $expectedToken
    ): void {
        $builderToken = new WebTokenBuilder(
            $tokenEncoder,
            $hashTokenGenerator
        );

        self::assertEquals($expectedToken, $builderToken->createToken($user));
    }

    public function validTokenDataProvider(): \Iterator
    {
        $user = $this->createConfiguredMock(User::class, [
            'ID' => new UserID(),
            'login' => 'login',
            'password' => '$2y$16$N3oKwYdU7H0A5wdc2I0XLev.E1snYUj67qqBdFLY2NTJGvF3ZvwVu',
            'isActive' => true,
            'equalsPassword' => true,
        ]);

        $hashTokenGenerator = new HashWebTokenGenerator('secret');

        $expires = time() + $hashTokenGenerator->lifeTime();

        $hashDataWebToken = new HashDataWebToken(
            $user->login(),
            $expires,
            $user->password()
        );

        $tokenParts = [
            base64_encode($user->login()),
            $expires,
            $hashTokenGenerator->generateHash($hashDataWebToken),
            $hashTokenGenerator->delimiter(),
        ];

        $token = base64_encode(implode($hashTokenGenerator->delimiter(), $tokenParts));

        $tokenEncoder = $this->createMock(TokenEncoderInterface::class);
        $tokenEncoder->method('encode')->willReturn($token);

        yield [
            $user,
            $hashTokenGenerator,
            $tokenEncoder,
            $token,
        ];
    }
}
