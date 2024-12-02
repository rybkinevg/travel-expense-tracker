<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Application\Command\CompleteTrip;

use App\Shared\Application\Command\CommandHandlerInterface;
use App\TravelExpenseTracker\Domain\Exception\TripNotFoundException;
use App\TravelExpenseTracker\Domain\Repository\TripRepositoryInterface;

final readonly class CompleteTripCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private TripRepositoryInterface $tripRepository,
    ) {}

    public function __invoke(CompleteTripCommand $command): void
    {
        $chatId = $command->chatId;

        $trip = $this->tripRepository->findActiveByChatId($chatId);

        if (null === $trip) {
            throw TripNotFoundException::notFoundActiveByChatId($chatId);
        }

        $trip->complete();

        $this->tripRepository->save($trip);
    }
}
