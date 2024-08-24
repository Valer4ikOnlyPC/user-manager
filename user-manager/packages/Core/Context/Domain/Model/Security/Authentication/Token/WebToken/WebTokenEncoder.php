<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Model\Security\Authentication\Token\WebToken;

use UserManager\Core\Common\Exception\AuthenticationException;
use UserManager\Core\Context\Domain\Model\Security\Authentication\Token\TokenEncoderInterface;

class WebTokenEncoder implements TokenEncoderInterface
{
    public function encode(array $tokenParts, string $delimiter = ''): string
    {
        return base64_encode(implode($delimiter, $tokenParts));
    }

    public function decode(string $token, string $delimiter = ''): array
    {
        if (false === $token = base64_decode($token, true)) {
            throw new AuthenticationException('Token contains a character from outside the base64 alphabet.');
        }

        $tokenParts = explode($delimiter, $token);

        if (3 !== count($tokenParts)) {
            throw new AuthenticationException('The token is invalid.');
        }

        [$login, $expires, $hash] = $tokenParts;

        if (false === $login = base64_decode($login, true)) {
            throw new AuthenticationException('$login contains a character from outside the base64 alphabet.');
        }

        return [
            $login,
            $expires,
            $hash,
        ];
    }
}
