<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Infrastructure\Doctrine\DBAL\Type;

use App\TravelExpenseTracker\Domain\ValueObject\ExpenseId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BigIntType;

final class ExpenseIdType extends BigIntType
{
    public const string TYPE_NAME = 'expense_id';

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ExpenseId
    {
        return ($value !== null)
            ? new ExpenseId($value)
            : null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        return $value?->value();
    }
}
