<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Domain\Repository;

use App\TravelExpenseTracker\Domain\Entity\Traveler;
use App\TravelExpenseTracker\Domain\ValueObject\ChatMemberUsername;
use App\TravelExpenseTracker\Domain\ValueObject\TripId;
use Doctrine\Common\Collections\Collection;

interface TravelerRepositoryInterface
{
    public function findByTripIdAndChatMemberUsername(
        TripId $tripId,
        ChatMemberUsername $chatMemberUsername
    ): ?Traveler;

    public function findByTripIdAndChatMemberUsernames(
        TripId $tripId,
        ChatMemberUsername ...$chatMemberUsernames
    ): Collection;
}
