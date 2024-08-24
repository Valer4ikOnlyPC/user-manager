<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Model\Security\Authentication\Token;

use UserManager\Core\Common\Exception\AuthenticationException;

interface TokenEncoderInterface
{
    /**
     * @param array<int, mixed> $tokenParts
     */
    public function encode(array $tokenParts, string $delimiter = ''): string;

    /**
     * @throws AuthenticationException
     *
     * @return array<int, mixed>
     */
    public function decode(string $token, string $delimiter = ''): array;
}
