<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Application\Command\CompleteTrip;

use App\Shared\Application\Command\CommandInterface;
use App\TravelExpenseTracker\Domain\ValueObject\ChatId;

final readonly class CompleteTripCommand implements CommandInterface
{
    public function __construct(
        public ChatId $chatId,
    ) {}
}
