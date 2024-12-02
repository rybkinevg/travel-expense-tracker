<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Application\Command\JoinTrip;

use App\Shared\Application\Command\CommandHandlerInterface;
use App\TravelExpenseTracker\Domain\Exception\TripNotFoundException;
use App\TravelExpenseTracker\Domain\Factory\TravelerFactory;
use App\TravelExpenseTracker\Domain\Repository\TripRepositoryInterface;

final readonly class JoinTripCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private TripRepositoryInterface $tripRepository,
        private TravelerFactory $travelerFactory,
    ) {}

    public function __invoke(JoinTripCommand $command): void
    {
        $chatId = $command->chatId;

        $trip = $this->tripRepository->findActiveByChatId($chatId);

        if (null === $trip) {
            throw TripNotFoundException::notFoundActiveByChatId($chatId);
        }

        $traveler = $this->travelerFactory->create(
            chatMemberUsername: $command->chatMemberUsername,
            fullName: $command->travelerFullName
        );

        $trip->addTraveler($traveler);

        $this->tripRepository->save($trip);
    }
}
