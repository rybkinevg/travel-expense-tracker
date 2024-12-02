<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Domain\Calculator;

use App\TravelExpenseTracker\Domain\Entity\Trip;

interface DebtTotalsCalculatorInterface
{
    /**
     * @return array<array-key, array{fullName: string, chatMemberUsername: string, debts: array{fullName: string, chatMemberUsername: string, amount: string}>
     */
    public function collect(Trip $trip): array;
}
