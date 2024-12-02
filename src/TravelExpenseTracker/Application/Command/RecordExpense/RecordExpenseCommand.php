<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Application\Command\RecordExpense;

use App\Shared\Application\Command\CommandInterface;
use App\TravelExpenseTracker\Domain\ValueObject\ChatId;
use App\TravelExpenseTracker\Domain\ValueObject\ChatMemberUsername;
use App\TravelExpenseTracker\Domain\ValueObject\ExpenseAmount;
use App\TravelExpenseTracker\Domain\ValueObject\ExpenseDescription;

final readonly class RecordExpenseCommand implements CommandInterface
{
    /**
     * @param ChatMemberUsername[] $debtorChatMemberUsernames
     */
    public function __construct(
        public ChatId $chatId,
        public ExpenseDescription $expenseDescription,
        public ExpenseAmount $expenseAmount,
        public ChatMemberUsername $payerChatMemberUsername,
        public array $debtorChatMemberUsernames,
    ) {}
}
