<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Application\Query\GetLastCompletedTripDebts;

use App\Shared\Application\Query\QueryHandlerInterface;
use App\TravelExpenseTracker\Domain\Calculator\DebtTotalsCalculatorInterface;
use App\TravelExpenseTracker\Domain\Repository\TripRepositoryInterface;

final readonly class GetLastCompletedTripDebtsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private TripRepositoryInterface $tripRepository,
        private DebtTotalsCalculatorInterface $debtTotalsCalculator,
    ) {}

    /**
     * @return null | array<array-key, array{fullName: string, chatMemberUsername: string, debts: array{fullName: string, chatMemberUsername: string, amount: string}>
     */
    public function __invoke(GetLastCompletedTripDebtsQuery $query): ?array
    {
        $chatId = $query->chatId;

        $trip = $this->tripRepository->findLastCompletedByChatId($chatId);

        if (null === $trip) {
            return null;
        }

        return $this->debtTotalsCalculator->collect($trip);
    }
}
