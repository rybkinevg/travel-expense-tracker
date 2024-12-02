<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Infrastructure\Doctrine\DBAL\Type;

use App\TravelExpenseTracker\Domain\ValueObject\TripId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BigIntType;

final class TripIdType extends BigIntType
{
    public const string TYPE_NAME = 'trip_id';

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?TripId
    {
        return ($value !== null)
            ? new TripId($value)
            : null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        return $value?->value();
    }
}
