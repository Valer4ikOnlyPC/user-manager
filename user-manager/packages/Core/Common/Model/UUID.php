<?php

declare(strict_types=1);

namespace UserManager\Core\Common\Model;

use Ramsey\Uuid\Uuid as UuidFactory;
use UserManager\Core\Common\Exception\InvalidArgumentException;

class UUID
{
    /**
     * @var string
     */
    protected $UUID;

    final public function __construct(string $UUID = null)
    {
        $this->setUUID($UUID);
    }

    final protected function setUUID(string $UUID = null): void
    {
        try {
            $this->UUID = null === $UUID
                ? UuidFactory::uuid4()->toString()
                : UuidFactory::fromString($UUID)->toString();
        } catch (\InvalidArgumentException $e) {
            throw new InvalidArgumentException('Некорректная строка UUID');
        }
    }

    final public function __toString(): string
    {
        return $this->UUID;
    }

    final public function equals(self $comparedID): bool
    {
        return static::class === get_class($comparedID) && (string) $this === (string) $comparedID;
    }

    final public static function fromString(string $uuid): self
    {
        return new self($uuid);
    }

    final public static function fromBytes(string $bytes): self
    {
        return new self(UuidFactory::fromBytes($bytes)->toString());
    }

    final public function getBytes(): string
    {
        return UuidFactory::fromString($this->UUID)->getBytes();
    }
}
