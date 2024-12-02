<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Application\Command\JoinTrip;

use App\Shared\Application\Command\CommandInterface;
use App\TravelExpenseTracker\Domain\ValueObject\ChatId;
use App\TravelExpenseTracker\Domain\ValueObject\ChatMemberUsername;
use App\TravelExpenseTracker\Domain\ValueObject\TravelerFullName;

final readonly class JoinTripCommand implements CommandInterface
{
    public function __construct(
        public ChatId $chatId,
        public ChatMemberUsername $chatMemberUsername,
        public TravelerFullName $travelerFullName,
    ) {}
}
