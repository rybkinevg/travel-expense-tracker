<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Domain\Repository;

use App\TravelExpenseTracker\Domain\Entity\Trip;
use App\TravelExpenseTracker\Domain\Exception\TripAlreadyExistException;
use App\TravelExpenseTracker\Domain\ValueObject\ChatId;
use App\TravelExpenseTracker\Domain\ValueObject\TripId;

interface TripRepositoryInterface
{
    public function findOneById(TripId $id): ?Trip;

    public function findActiveByChatId(ChatId $chatId): ?Trip;

    public function findLastCompletedByChatId(ChatId $chatId): ?Trip;

    /**
     * @throws TripAlreadyExistException
     * @throws \RuntimeException
     */
    public function save(Trip $trip): void;
}
