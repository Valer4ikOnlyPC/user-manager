<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Model\Security\Authentication\Token\WebToken;

use UserManager\Core\Context\Domain\Model\Security\Authentication\Token\HashDataInterface;
use UserManager\Core\Context\Domain\Model\Security\Authentication\Token\HashTokenGeneratorInterface;

class HashWebTokenGenerator implements HashTokenGeneratorInterface
{
    private const TOKEN_DELIMITER = ':';

    private const TOKEN_LIFETIME = 60 * 60 * 24 * 7;

    /**
     * @var string
     */
    private $secret;

    public function __construct(string $secret)
    {
        $this->setSecret($secret);
    }

    public function secret(): string
    {
        return $this->secret;
    }

    public function delimiter(): ?string
    {
        return self::TOKEN_DELIMITER;
    }

    public function lifeTime(): int
    {
        return self::TOKEN_LIFETIME;
    }

    private function setSecret(string $secret): void
    {
        $this->secret = $secret;
    }

    /**
     * @param HashDataWebToken $data
     */
    public function generateHash(HashDataInterface $data): string
    {
        return hash_hmac(
            'sha256',
            $data->login() . $this->delimiter() . $data->expires() . $this->delimiter() . $data->password(),
            $this->secret()
        );
    }
}
