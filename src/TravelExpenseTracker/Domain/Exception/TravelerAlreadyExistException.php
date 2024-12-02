<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Domain\Exception;

use App\TravelExpenseTracker\Domain\ValueObject\ChatMemberUsername;

class TravelerAlreadyExistException extends \RuntimeException
{
    public static function uniquePerTrip(ChatMemberUsername $username, ?\Exception $prev = null): self
    {
        $message = sprintf(
            'Traveler with username [%s] already exists in trip',
            $username->value()
        );

        return new self(
            message: $message,
            code: $prev?->getCode() ?? 0,
            previous: $prev
        );
    }
}
