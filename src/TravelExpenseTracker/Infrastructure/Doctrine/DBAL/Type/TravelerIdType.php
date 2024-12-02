<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Infrastructure\Doctrine\DBAL\Type;

use App\TravelExpenseTracker\Domain\ValueObject\TravelerId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BigIntType;

final class TravelerIdType extends BigIntType
{
    public const string TYPE_NAME = 'traveler_id';

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?TravelerId
    {
        return (null !== $value)
            ? new TravelerId($value)
            : null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        return $value?->value();
    }
}
