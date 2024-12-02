<?php

declare(strict_types=1);

namespace App\TravelExpenseTracker\Domain\Calculator;

use App\Shared\Domain\ValueObject\Money;
use App\TravelExpenseTracker\Domain\Entity\Expense;
use App\TravelExpenseTracker\Domain\Entity\Traveler;
use App\TravelExpenseTracker\Domain\Entity\Trip;
use App\TravelExpenseTracker\Domain\ValueObject\ExpenseAmount;
use Doctrine\Common\Collections\Collection;

// TODO: create storage (Redis for example) for totals to make them accessible many time w\o collecting
final readonly class SharedDebtTotalsCalculator implements DebtTotalsCalculatorInterface
{
    public function collect(Trip $trip): array
    {
        /** @var Collection<Expense> $expenses */
        $expenses = $trip->getExpenses();

        $rawTotals = [];

        // cache travelers to get their info when totals will be collected
        $cachedTravelers = [];

        // calculating debt total per expense
        foreach ($expenses as $expense) {
            $payerId = $expense->getPayer()->getId()->value();
            $debtors = $expense->getDebtors();

            if ($debtors->isEmpty()) {
                continue;
            }

            $divider = new Money((string) $debtors->count());

            /** @var Traveler $debtor */
            foreach ($debtors as $debtor) {
                $debtorId = $debtor->getId()->value();

                $cachedTravelers[$debtorId] = $debtor;

                if (!isset($rawTotals[$debtorId][$payerId])) {
                    $rawTotals[$debtorId][$payerId] = new Money('0');
                }

                /** @var ExpenseAmount $currentAmount */
                $currentAmount = $rawTotals[$debtorId][$payerId];
                $amount = $expense->getAmount()->div($divider);
                $newAmount = $currentAmount->add($amount);

                $rawTotals[$debtorId][$payerId] = $newAmount;
            }
        }

        // handling P2P debts
        foreach ($rawTotals as $debtorId => $debts) {
            foreach ($debts as $payerId => $amount) {
                $amount = $rawTotals[$debtorId][$payerId] ?? null;
                $refAmount = $rawTotals[$payerId][$debtorId] ?? null;

                if (null === $refAmount || null === $amount) {
                    continue;
                }

                $amount = new Money((string) $amount);
                $refAmount = new Money((string) $refAmount);

                if ($refAmount->isGreaterThan($amount)) {
                    $rawTotals[$payerId][$debtorId] = $refAmount->sub($amount);

                    unset($rawTotals[$debtorId][$payerId]);
                } elseif ($refAmount->isLessThan($amount)) {
                    $rawTotals[$debtorId][$payerId] = $amount->sub($refAmount);

                    unset($rawTotals[$payerId][$debtorId]);
                } else {
                    unset($rawTotals[$debtorId][$payerId]);
                    unset($rawTotals[$payerId][$debtorId]);
                }
            }
        }

        $totals = [];

        foreach ($rawTotals as $debtorId => $debts) {
            if (empty($debts)) {
                continue;
            }

            $traveler = $cachedTravelers[$debtorId];

            $totals[$debtorId] = [
                'fullName' => $traveler->getFullName()->value(),
                'chatMemberUsername' => $traveler->getChatMemberUsername()->value(),
            ];

            foreach ($debts as $payerId => $debtAmount) {
                $traveler = $cachedTravelers[$payerId];

                $totals[$debtorId]['debts'][] = [
                    'fullName' => $traveler->getFullName()->value(),
                    'chatMemberUsername' => $traveler->getChatMemberUsername()->value(),
                    'amount' => $debtAmount->value()
                ];
            }
        }

        return $totals;
    }
}
