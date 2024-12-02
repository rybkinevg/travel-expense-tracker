<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Domain\Exception;

use App\TravelExpenseTracker\Domain\ValueObject\ChatId;

class TripNotFoundException extends \RuntimeException
{
    public static function notFoundActiveByChatId(ChatId $chatId): self
    {
        $message = sprintf(
            'There is no active trip for chat ID [%s]',
            $chatId->value()
        );

        return new self($message);
    }
}
