<?php

declare(strict_types=1);

namespace UserManager\tests\Core\Context\Domain\Model\Security\Authentication\Token\WebToken;

use PHPUnit\Framework\TestCase;
use UserManager\Core\Common\Exception\AuthenticationException;
use UserManager\Core\Context\Domain\Model\Security\Authentication\Token\WebToken\WebTokenEncoder;

class WebTokenEncoderTest extends TestCase
{
    /**
     * @param array<int, mixed> $tokenParts
     *
     * @dataProvider validTokenPartsDataProvider
     */
    public function testEncodeToken(
        array $tokenParts,
        string $delimiter,
        string $expectedResult
    ): void {
        $webTokenEncoder = new WebTokenEncoder();
        $result = $webTokenEncoder->encode($tokenParts, $delimiter);

        self::assertEquals($expectedResult, $result);
    }

    public function validTokenPartsDataProvider(): \Iterator
    {
        $tokenParts = [
            'login',
            time() + 60 * 60 * 24 * 7,
            'hash',
        ];

        $delimiter = ':';

        yield [
            $tokenParts,
            $delimiter,
            base64_encode(implode($tokenParts, $delimiter)),
        ];

        $delimiter = '.';

        yield [
            $tokenParts,
            $delimiter,
            base64_encode(implode($tokenParts, $delimiter)),
        ];
    }

    /**
     * @param array<int, mixed> $expectedResult
     *
     * @dataProvider validTokenDataProvider
     */
    public function testDecodeToken(
        string $token,
        string $delimiter,
        array $expectedResult
    ): void {
        $webTokenEncoder = new WebTokenEncoder();

        $result = $webTokenEncoder->decode($token, $delimiter);

        self::assertEquals($expectedResult, $result);
    }

    public function validTokenDataProvider(): \Iterator
    {
        $expires = time() + 60 * 60 * 24 * 7;

        $tokenParts = [
            base64_encode('login'),
            $expires,
            'hash',
        ];

        $delimiter = ':';

        $token = base64_encode(implode($tokenParts, $delimiter));

        yield [
            $token,
            $delimiter,
            [
                'login',
                $expires,
                'hash',
            ],
        ];
    }

    /**
     * @dataProvider invalidTokenDataProvider
     */
    public function testItCanThrowsExceptionForInvalidTokenDecoding(
        string $token,
        string $delimiter,
        string $exceptionMessage,
        string $exceptionClass
    ): void {
        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);

        $webTokenEncoder = new WebTokenEncoder();

        $webTokenEncoder->decode($token, $delimiter);
    }

    public function invalidTokenDataProvider(): \Iterator
    {
        $delimiter = ':';

        yield [
            'TOKEN',
            $delimiter,
            'Token contains a character from outside the base64 alphabet.',
            AuthenticationException::class,
        ];

        $tokenParts = [
            'login',
            'hash',
        ];

        $token = base64_encode(implode($tokenParts, $delimiter));

        yield [
            $token,
            $delimiter,
            'The token is invalid.',
            AuthenticationException::class,
        ];

        $tokenParts = [
            'login',
            time() + 60 * 60 * 24,
            'hash',
        ];

        $token = base64_encode(implode($tokenParts, $delimiter));

        yield [
            $token,
            $delimiter,
            '$login contains a character from outside the base64 alphabet.',
            AuthenticationException::class,
        ];
    }
}
