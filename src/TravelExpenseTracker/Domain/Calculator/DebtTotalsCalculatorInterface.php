<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Domain\Calculator;

use App\TravelExpenseTracker\Domain\Entity\Trip;

interface DebtTotalsCalculatorInterface
{
    public function collect(Trip $trip): array;
}
