<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Application\Command\StartTrip;

use App\Shared\Application\Command\CommandInterface;
use App\TravelExpenseTracker\Domain\ValueObject\ChatId;
use App\TravelExpenseTracker\Domain\ValueObject\TripTitle;

final readonly class StartTripCommand implements CommandInterface
{
    public function __construct(
        public ChatId $chatId,
        public TripTitle $tripTitle,
    ) {}
}
