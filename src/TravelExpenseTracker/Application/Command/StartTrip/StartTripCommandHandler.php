<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Application\Command\StartTrip;

use App\Shared\Application\Command\CommandHandlerInterface;
use App\TravelExpenseTracker\Domain\Factory\TripFactory;
use App\TravelExpenseTracker\Domain\Repository\TripRepositoryInterface;

final readonly class StartTripCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private TripFactory $tripFactory,
        private TripRepositoryInterface $tripRepository,
    ) {}

    public function __invoke(StartTripCommand $command): void
    {
        $trip = $this->tripFactory->create(
            chatId: $command->chatId,
            title: $command->tripTitle
        );

        $trip->start();

        $this->tripRepository->save($trip);
    }
}
