<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Domain\Factory;

use App\TravelExpenseTracker\Domain\Entity\Expense;
use App\TravelExpenseTracker\Domain\ValueObject\ExpenseAmount;
use App\TravelExpenseTracker\Domain\ValueObject\ExpenseDescription;

final readonly class ExpenseFactory
{
    public function create(
        ExpenseDescription $description,
        ExpenseAmount $amount,
    ): Expense {
        return new Expense(
            description: $description,
            amount: $amount
        );
    }
}
