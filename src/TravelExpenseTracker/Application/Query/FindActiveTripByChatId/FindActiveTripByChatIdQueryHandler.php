<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Application\Query\FindActiveTripByChatId;

use App\Shared\Application\Query\QueryHandlerInterface;
use App\TravelExpenseTracker\Application\DTO\TripData;
use App\TravelExpenseTracker\Domain\Repository\TripRepositoryInterface;

final readonly class FindActiveTripByChatIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private TripRepositoryInterface $tripRepository,
    ) {}

    public function __invoke(FindActiveTripByChatIdQuery $query): ?TripData
    {
        $trip = $this->tripRepository->findActiveByChatId($query->chatId);

        return $trip
            ? TripData::fromEntity($trip)
            : null;
    }
}
