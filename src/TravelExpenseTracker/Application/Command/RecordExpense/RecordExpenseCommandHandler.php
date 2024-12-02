<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Application\Command\RecordExpense;

use App\Shared\Application\Command\CommandHandlerInterface;
use App\TravelExpenseTracker\Domain\Entity\Traveler;
use App\TravelExpenseTracker\Domain\Exception\TravelerNotFoundException;
use App\TravelExpenseTracker\Domain\Exception\TripNotFoundException;
use App\TravelExpenseTracker\Domain\Factory\ExpenseFactory;
use App\TravelExpenseTracker\Domain\Repository\TravelerRepositoryInterface;
use App\TravelExpenseTracker\Domain\Repository\TripRepositoryInterface;
use Doctrine\Common\Collections\Collection;

final readonly class RecordExpenseCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private TripRepositoryInterface $tripRepository,
        private ExpenseFactory $expenseFactory,
        private TravelerRepositoryInterface $travelerRepository,
    ) {}

    public function __invoke(RecordExpenseCommand $command): void
    {
        $chatId = $command->chatId;

        $trip = $this->tripRepository->findActiveByChatId($command->chatId);

        if (null === $trip) {
            throw TripNotFoundException::notFoundActiveByChatId($chatId);
        }

        $chatMemberUsernames = array_merge(
            [$command->payerChatMemberUsername],
            $command->debtorChatMemberUsernames
        );

        $travelers = $this->travelerRepository->findByTripIdAndChatMemberUsernames(
            $trip->getId(),
            ...$chatMemberUsernames
        );

        $this->assertTravelersMatchesChatMemberUsernames($chatMemberUsernames, $travelers);

        $expense = $this->expenseFactory->create(
            description: $command->expenseDescription,
            amount: $command->expenseAmount,
        );

        /** @var Traveler $traveler */
        foreach ($travelers as $traveler) {
            if ($traveler->getChatMemberUsername()->equals($command->payerChatMemberUsername)) {
                $expense->setPayer($traveler);

                continue;
            }

            $expense->addDebtor($traveler);
        }

        $trip->addExpense($expense);

        $this->tripRepository->save($trip);
    }

    private function assertTravelersMatchesChatMemberUsernames(
        array $chatMemberUsernames,
        Collection $travelers
    ): void {
        foreach ($chatMemberUsernames as $chatMemberUsername) {
            $isExists = $travelers->exists(
                static function (int $key, Traveler $traveler) use ($chatMemberUsername): bool {
                    return $traveler->getChatMemberUsername()->equals($chatMemberUsername);
                }
            );

            if (!$isExists) {
                throw TravelerNotFoundException::notFoundByChatMemberUsername($chatMemberUsername);
            }
        }
    }
}
