<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Domain\Exception;

class TripAlreadyExistException extends \RuntimeException
{
    public static function uniquePerChat(?\Exception $prev = null): self
    {
        $message = 'Only one active trip per chat is allowed';

        return new self(
            message: $message,
            code: $prev?->getCode() ?? 0,
            previous: $prev
        );
    }
}
