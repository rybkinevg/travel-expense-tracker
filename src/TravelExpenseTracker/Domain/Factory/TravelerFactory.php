<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Domain\Factory;

use App\TravelExpenseTracker\Domain\Entity\Traveler;
use App\TravelExpenseTracker\Domain\ValueObject\ChatMemberUsername;
use App\TravelExpenseTracker\Domain\ValueObject\TravelerFullName;

final readonly class TravelerFactory
{
    public function create(
        ChatMemberUsername $chatMemberUsername,
        TravelerFullName $fullName
    ): Traveler {
        return new Traveler(
            chatMemberUsername: $chatMemberUsername,
            fullName: $fullName
        );
    }
}
