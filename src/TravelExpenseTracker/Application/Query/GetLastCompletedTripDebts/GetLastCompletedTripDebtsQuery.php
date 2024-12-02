<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Application\Query\GetLastCompletedTripDebts;

use App\Shared\Application\Query\QueryInterface;
use App\TravelExpenseTracker\Domain\ValueObject\ChatId;

final readonly class GetLastCompletedTripDebtsQuery implements QueryInterface
{
    public function __construct(
        public ChatId $chatId,
    ) {}
}
