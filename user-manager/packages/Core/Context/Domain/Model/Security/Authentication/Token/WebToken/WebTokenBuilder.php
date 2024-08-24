<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Model\Security\Authentication\Token\WebToken;

use UserManager\Core\Context\Domain\Model\Security\Authentication\Token\HashTokenGeneratorInterface;
use UserManager\Core\Context\Domain\Model\Security\Authentication\Token\TokenBuilderInterface;
use UserManager\Core\Context\Domain\Model\Security\Authentication\Token\TokenEncoderInterface;
use UserManager\Core\Context\Domain\Model\Security\UserInterface;

class WebTokenBuilder implements TokenBuilderInterface
{
    /**
     * @var TokenEncoderInterface
     */
    private $tokenEncoder;

    /**
     * @var HashTokenGeneratorInterface
     */
    private $hashTokenGenerator;

    public function __construct(
        TokenEncoderInterface $tokenEncoder,
        HashTokenGeneratorInterface $hashTokenGenerator
    ) {
        $this->setTokenEncoder($tokenEncoder);
        $this->setHashTokenGenerator($hashTokenGenerator);
    }

    private function tokenEncoder(): TokenEncoderInterface
    {
        return $this->tokenEncoder;
    }

    private function setTokenEncoder(TokenEncoderInterface $tokenEncoder): void
    {
        $this->tokenEncoder = $tokenEncoder;
    }

    private function hashTokenGenerator(): HashTokenGeneratorInterface
    {
        return $this->hashTokenGenerator;
    }

    private function setHashTokenGenerator(HashTokenGeneratorInterface $hashTokenGenerator): void
    {
        $this->hashTokenGenerator = $hashTokenGenerator;
    }

    public function createToken(UserInterface $user): string
    {
        $expires = time() + $this->hashTokenGenerator()->lifeTime();

        return $this->tokenEncoder()->encode(
            [
                base64_encode($user->login()),
                $expires,
                $this->hashTokenGenerator()->generateHash(
                    new HashDataWebToken(
                        $user->login(),
                        $expires,
                        $user->password()
                    )
                ),
            ],
            $this->hashTokenGenerator()->delimiter()
        );
    }
}
