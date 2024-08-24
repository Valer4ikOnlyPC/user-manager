<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use UserManager\Core\Common\Model\UUID;

abstract class DoctrineUuidBinaryType extends Type
{
    /**
     * @var string
     */
    public const NAME = 'uuid_binary';

    /**
     * @param mixed[] $fieldDeclaration
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getBinaryTypeDeclarationSQL(
            [
                'length' => '16',
                'fixed' => true,
            ]
        );
    }

    /**
     * @param string|UUID|null $value
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?UUID
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof UUID) {
            return $this->convertToConcreteClass($value);
        }

        try {
            $uuid = UUID::fromBytes($value);
        } catch (InvalidArgumentException $e) {
            throw ConversionException::conversionFailed($value, static::NAME);
        }

        return $this->convertToConcreteClass($uuid);
    }

    /**
     * @return mixed
     */
    abstract protected function convertToConcreteClass(UUID $uuid);

    /**
     * @param mixed $value
     *
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof UUID) {
            return $value->getBytes();
        }

        try {
            if (is_string($value) || method_exists($value, '__toString')) {
                return UUID::fromString((string) $value)->getBytes();
            }
        } catch (InvalidArgumentException $e) {
        }

        throw ConversionException::conversionFailed($value, static::NAME);
    }

    public function getName(): string
    {
        return static::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
