<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Domain\Exception;

use App\TravelExpenseTracker\Domain\ValueObject\ChatMemberUsername;

class TravelerNotFoundException extends \RuntimeException
{
    public static function notFoundByChatMemberUsername(ChatMemberUsername $username): self
    {
        $message = sprintf(
            'Traveler with chat member username [%s] not found',
            $username->value()
        );

        return new self($message);
    }
}
