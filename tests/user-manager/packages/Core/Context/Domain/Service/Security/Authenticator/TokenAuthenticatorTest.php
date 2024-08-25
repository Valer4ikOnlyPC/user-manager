<?php

declare(strict_types=1);

namespace UserManager\tests\Core\Context\Domain\Service\Security\Authenticator;

use PHPUnit\Framework\TestCase;
use UserManager\Core\Common\Exception\AuthenticationException;
use UserManager\Core\Common\Exception\AuthenticationLogicException;
use UserManager\Core\Context\Domain\Model\Security\Authentication\AuthenticationRequest\AuthenticationRequestInterface;
use UserManager\Core\Context\Domain\Model\Security\Authentication\AuthenticationResponse;
use UserManager\Core\Context\Domain\Model\Security\Authentication\Token\HashTokenGeneratorInterface;
use UserManager\Core\Context\Domain\Model\Security\Authentication\Token\TokenEncoderInterface;
use UserManager\Core\Context\Domain\Model\Security\Authentication\UserChecker\UserCheckerInterface;
use UserManager\Core\Context\Domain\Model\User\User;
use UserManager\Core\Context\Domain\Model\User\UserID;
use UserManager\Core\Context\Domain\Model\User\UserRepositoryInterface;
use UserManager\Core\Context\Domain\Service\Security\Authenticator\TokenAuthenticator;

class TokenAuthenticatorTest extends TestCase
{
    public function testItCanAuthenticate(): void
    {
        $user = $this->createConfiguredMock(User::class, [
            'ID' => new UserID(),
            'login' => 'test',
            'password' => '$2y$16$N3oKwYdU7H0A5wdc2I0XLev.E1snYUj67qqBdFLY2NTJGvF3ZvwVu',
            'equalsPassword' => true,
        ]);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('findOneBy')->willReturn($user);

        $userChecker = $this->createMock(UserCheckerInterface::class);
        $userChecker->method('checkUser')->with($user);

        $tokenEncoder = $this->createMock(TokenEncoderInterface::class);
        $tokenEncoder->method('decode')->willReturn(['test', time() + 60 * 60 * 24 * 7, 'hash']);

        $hashTokenGenerator = $this->createConfiguredMock(HashTokenGeneratorInterface::class, [
            'secret' => 'secret',
            'delimiter' => ':',
            'lifeTime' => 60 * 60 * 24 * 7,
            'generateHash' => 'hash',
        ]);

        $authenticator = new TokenAuthenticator(
            $tokenEncoder,
            $hashTokenGenerator,
            $userRepository,
            $userChecker
        );

        $request = $this->createMock(AuthenticationRequestInterface::class);
        $request->method('authenticationToken')->willReturn('token');

        $response = $authenticator->authenticate($request);

        self::assertTrue($authenticator->supports($request));
        self::assertInstanceOf(AuthenticationResponse::class, $response);
    }

    public function testItCanThrowExceptionsForInvalidRequest(): void
    {
        $this->expectException(AuthenticationLogicException::class);
        $this->expectExceptionMessage('$request do not have token.');

        $authenticator = new TokenAuthenticator(
            $this->createMock(TokenEncoderInterface::class),
            $this->createMock(HashTokenGeneratorInterface::class),
            $this->createMock(UserRepositoryInterface::class),
            $this->createMock(UserCheckerInterface::class)
        );

        $authenticator->authenticate($this->createMock(AuthenticationRequestInterface::class));
    }

    public function testItCanThrowExceptionsForInvalidHashToken(): void
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('The token\'s hash is invalid.');

        $user = $this->createConfiguredMock(User::class, [
            'ID' => new UserID(),
            'login' => 'test',
            'password' => '$2y$16$N3oKwYdU7H0A5wdc2I0XLev.E1snYUj67qqBdFLY2NTJGvF3ZvwVu',
            'equalsPassword' => true,
        ]);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('findOneBy')->willReturn($user);

        $userChecker = $this->createMock(UserCheckerInterface::class);
        $userChecker->method('checkUser')->with($user);

        $tokenEncoder = $this->createMock(TokenEncoderInterface::class);
        $tokenEncoder->method('decode')->willReturn(['test', time() + 60 * 60 * 24 * 7, 'hash']);

        $hashTokenGenerator = $this->createConfiguredMock(HashTokenGeneratorInterface::class, [
            'secret' => 'secret',
            'delimiter' => ':',
            'lifeTime' => 60 * 60 * 24 * 7,
            'generateHash' => 'hash2',
        ]);

        $authenticator = new TokenAuthenticator(
            $tokenEncoder,
            $hashTokenGenerator,
            $userRepository,
            $userChecker
        );

        $request = $this->createMock(AuthenticationRequestInterface::class);
        $request->method('authenticationToken')->willReturn('token');

        $authenticator->authenticate($request);
    }

    public function testItCanThrowExceptionsForExpiredToken(): void
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('The token has expired.');

        $user = $this->createConfiguredMock(User::class, [
            'ID' => new UserID(),
            'login' => 'test',
            'password' => '$2y$16$N3oKwYdU7H0A5wdc2I0XLev.E1snYUj67qqBdFLY2NTJGvF3ZvwVu',
            'equalsPassword' => true,
        ]);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('findOneBy')->willReturn($user);

        $userChecker = $this->createMock(UserCheckerInterface::class);
        $userChecker->method('checkUser')->with($user);

        $tokenEncoder = $this->createMock(TokenEncoderInterface::class);
        $tokenEncoder->method('decode')->willReturn(['test', time() - 60 * 60 * 24 * 7, 'hash']);

        $hashTokenGenerator = $this->createConfiguredMock(HashTokenGeneratorInterface::class, [
            'secret' => 'secret',
            'delimiter' => ':',
            'lifeTime' => 60 * 60 * 24 * 7,
            'generateHash' => 'hash',
        ]);

        $authenticator = new TokenAuthenticator(
            $tokenEncoder,
            $hashTokenGenerator,
            $userRepository,
            $userChecker
        );

        $request = $this->createMock(AuthenticationRequestInterface::class);
        $request->method('authenticationToken')->willReturn('token');

        $authenticator->authenticate($request);
    }
}
