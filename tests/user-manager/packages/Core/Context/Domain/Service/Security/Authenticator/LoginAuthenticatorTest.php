<?php

declare(strict_types=1);

namespace UserManager\tests\Core\Context\Domain\Service\Security\Authenticator;

use PHPUnit\Framework\TestCase;
use UserManager\Core\Common\Exception\AuthenticationException;
use UserManager\Core\Common\Exception\AuthenticationLogicException;
use UserManager\Core\Context\Domain\Model\Security\Authentication\AuthenticationRequest\AuthenticationRequestInterface;
use UserManager\Core\Context\Domain\Model\Security\Authentication\AuthenticationRequest\LoginAuthenticationRequestInterface;
use UserManager\Core\Context\Domain\Model\Security\Authentication\AuthenticationResponse;
use UserManager\Core\Context\Domain\Model\Security\Authentication\UserChecker\UserCheckerInterface;
use UserManager\Core\Context\Domain\Model\User\User;
use UserManager\Core\Context\Domain\Model\User\UserID;
use UserManager\Core\Context\Domain\Model\User\UserRepositoryInterface;
use UserManager\Core\Context\Domain\Service\Security\Authenticator\LoginAuthenticator;

class LoginAuthenticatorTest extends TestCase
{
    public function testItCanAuthenticate(): void
    {
        $user = $this->createConfiguredMock(User::class, [
            'ID' => new UserID(),
            'equalsPassword' => true,
        ]);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('findOneBy')->willReturn($user);

        $userChecker = $this->createMock(UserCheckerInterface::class);
        $userChecker->method('checkUser')->with($user);

        $authenticator = new LoginAuthenticator(
            $userRepository,
            $userChecker
        );

        $response = $authenticator->authenticate(
            $this->createMock(LoginAuthenticationRequestInterface::class)
        );

        self::assertTrue(
            $authenticator->supports(
                $this->createMock(LoginAuthenticationRequestInterface::class)
            )
        );

        self::assertInstanceOf(AuthenticationResponse::class, $response);
    }

    public function testItCanThrowExceptionsForInvalidRequest(): void
    {
        $this->expectException(AuthenticationLogicException::class);
        $this->expectExceptionMessage(
            sprintf('$request must implement interface "%s"', LoginAuthenticationRequestInterface::class)
        );

        $authenticator = new LoginAuthenticator(
            $this->createMock(UserRepositoryInterface::class),
            $this->createMock(UserCheckerInterface::class)
        );

        $authenticator->authenticate($this->createMock(AuthenticationRequestInterface::class));
    }

    public function testItCanThrowExceptionsForInvalidPassword(): void
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Bad password.');

        $user = $this->createConfiguredMock(User::class, [
            'ID' => new UserID(),
            'equalsPassword' => false,
        ]);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('findOneBy')->willReturn($user);

        $userChecker = $this->createMock(UserCheckerInterface::class);
        $userChecker->method('checkUser')->with($user);

        $authenticator = new LoginAuthenticator(
            $userRepository,
            $userChecker
        );

        $authenticator->authenticate(
            $this->createMock(LoginAuthenticationRequestInterface::class)
        );
    }
}
