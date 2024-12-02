<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Domain\Exception;

class TripAlreadyCompletedException extends \RuntimeException
{
    public static function alreadyCompleted(): self
    {
        return new self('Unable to start already completed trip');
    }
}
