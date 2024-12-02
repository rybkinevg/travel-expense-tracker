<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Domain\Factory;

use App\TravelExpenseTracker\Domain\Entity\Trip;
use App\TravelExpenseTracker\Domain\Specification\TripSpecificationPool;
use App\TravelExpenseTracker\Domain\ValueObject\ChatId;
use App\TravelExpenseTracker\Domain\ValueObject\TripTitle;

final readonly class TripFactory
{
    public function __construct(
        private TripSpecificationPool $specificationPool,
    ) {}

    public function create(
        ChatId $chatId,
        TripTitle $title,
    ): Trip {
        return new Trip(
            chatId: $chatId,
            title: $title,
            specificationPool: $this->specificationPool
        );
    }
}
